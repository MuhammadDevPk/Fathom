#!/usr/bin/env python3
"""
Agent Capture Script for Antigravity IDE and Claude Code.
Monitors session transcripts and writes clean, turn-by-turn logs to .agent-logs/.
"""

import sys
import os
import re
import json
import time
import glob
from datetime import datetime, timezone
from pathlib import Path

WORKSPACE_DIR = Path(__file__).resolve().parent.parent
LOGS_DIR = WORKSPACE_DIR / ".agent-logs"
BRAIN_DIR = Path(os.path.expanduser("~/.gemini/antigravity-ide/brain"))

AUTHOR = "MuhammadDevPk"
PROJECT = "Fathom"
TOOL_NAME = "antigravity-ide"
DEFAULT_MODEL = "Gemini 3.8 Flash (High)"

def clean_prompt(raw_text: str) -> str:
    """Extract clean user prompt from raw transcript content."""
    if not raw_text:
        return ""
    # Extract content inside <USER_REQUEST>...</USER_REQUEST> if present
    match = re.search(r"<USER_REQUEST>\s*(.*?)\s*</USER_REQUEST>", raw_text, re.DOTALL)
    if match:
        return match.group(1).strip()
    
    # Strip common system envelope tags if any
    cleaned = re.sub(r"<ADDITIONAL_METADATA>.*?</ADDITIONAL_METADATA>", "", raw_text, flags=re.DOTALL)
    cleaned = re.sub(r"<USER_SETTINGS_CHANGE>.*?</USER_SETTINGS_CHANGE>", "", cleaned, flags=re.DOTALL)
    cleaned = re.sub(r"<SYSTEM_MESSAGE>.*?</SYSTEM_MESSAGE>", "", cleaned, flags=re.DOTALL)
    return cleaned.strip()

def clean_response(raw_text: str) -> str:
    """Extract clean response text."""
    if not raw_text:
        return ""
    return raw_text.strip()

def parse_iso_time(ts_str: str) -> datetime:
    """Parse ISO timestamp to UTC datetime object."""
    if not ts_str:
        return datetime.now(timezone.utc)
    ts_str = ts_str.replace("Z", "+00:00")
    try:
        dt = datetime.fromisoformat(ts_str)
        return dt.astimezone(timezone.utc)
    except Exception:
        return datetime.now(timezone.utc)

def format_timestamp(dt: datetime) -> str:
    """Format datetime as UTC ISO-8601 with milliseconds."""
    utc_dt = dt.astimezone(timezone.utc)
    return utc_dt.strftime("%Y-%m-%dT%H:%M:%S.%f")[:-3] + "Z"

def is_workspace_transcript(transcript_path: Path) -> bool:
    """Check if the transcript belongs to this workspace."""
    workspace_str = str(WORKSPACE_DIR)
    workspace_suffix = f"/8x/{WORKSPACE_DIR.name}"
    try:
        with open(transcript_path, "r", encoding="utf-8", errors="replace") as f:
            for line in f:
                if workspace_str in line or workspace_suffix in line:
                    return True
    except Exception:
        pass
    return False

def parse_transcript(transcript_path: Path):
    """Parse transcript_full.jsonl into exchanges."""
    conversation_id = transcript_path.parent.parent.parent.name
    exchanges = []
    
    current_prompt = None
    current_prompt_time = None
    current_model = DEFAULT_MODEL
    last_response = None
    last_response_time = None

    try:
        with open(transcript_path, "r", encoding="utf-8", errors="replace") as f:
            for line in f:
                line = line.strip()
                if not line:
                    continue
                try:
                    step = json.loads(line)
                except Exception:
                    continue

                step_type = step.get("type")
                created_at = step.get("created_at")

                if step_type == "USER_INPUT":
                    raw_content = step.get("content", "")
                    
                    # Check for model selection change in user input metadata
                    model_match = re.search(r"setting `Model Selection`\s+from\s+.*?\s+to\s+([^\r\n<]+?)\.\s*No need", raw_content)
                    if model_match:
                        current_model = model_match.group(1).strip()

                    # If there was a previous prompt, save that exchange
                    if current_prompt is not None:
                        exchanges.append({
                            "prompt": current_prompt,
                            "prompt_time": current_prompt_time,
                            "model": current_model,
                            "response": last_response,
                            "response_time": last_response_time,
                        })
                    
                    raw_content = step.get("content", "")
                    current_prompt = clean_prompt(raw_content)
                    current_prompt_time = parse_iso_time(created_at)
                    last_response = None
                    last_response_time = None

                elif step_type == "PLANNER_RESPONSE":
                    content = step.get("content")
                    # If this step contains text response without tool calls
                    if content and not step.get("tool_calls"):
                        last_response = clean_response(content)
                        last_response_time = parse_iso_time(created_at)

            # Append the active or last turn
            if current_prompt is not None:
                exchanges.append({
                    "prompt": current_prompt,
                    "prompt_time": current_prompt_time,
                    "model": current_model,
                    "response": last_response,
                    "response_time": last_response_time,
                })

    except Exception as e:
        print(f"Error reading transcript {transcript_path}: {e}", file=sys.stderr)
        return None

    if not exchanges:
        return None

    return {
        "conversation_id": conversation_id,
        "exchanges": exchanges,
        "first_prompt_time": exchanges[0]["prompt_time"],
        "last_prompt_time": exchanges[-1]["prompt_time"],
    }

def write_session_log(session_data: dict) -> Path:
    """Generate or update the markdown log file for a session."""
    LOGS_DIR.mkdir(parents=True, exist_ok=True)
    
    conv_id = session_data["conversation_id"]
    short_id = conv_id[:8]
    first_dt = session_data["first_prompt_time"]
    date_str = first_dt.strftime("%Y-%m-%d")
    time_prefix = first_dt.strftime("%Y-%m-%d_%H-%M-%S")
    
    log_filename = f"{time_prefix}_{conv_id}.md"
    log_path = LOGS_DIR / log_filename

    total_exchanges = len(session_data["exchanges"])
    first_prompt_iso = format_timestamp(session_data["first_prompt_time"])
    last_prompt_iso = format_timestamp(session_data["last_prompt_time"])

    lines = [
        "---",
        f"session_id: {conv_id}",
        f"date: {date_str}",
        f"author: {AUTHOR}",
        f"model: {DEFAULT_MODEL}",
        f"tool: {TOOL_NAME}",
        f"project: {PROJECT}",
        f"total_exchanges: {total_exchanges}",
        f"first_prompt_time: {first_prompt_iso}",
        f"last_prompt_time: {last_prompt_iso}",
        "---",
        "",
        f"# Session Log - {date_str}",
        "",
        f"Session: `{short_id}` | Project: `{PROJECT}` | Author: `{AUTHOR}`",
        "",
        "---",
        ""
    ]

    for idx, ex in enumerate(session_data["exchanges"], start=1):
        prompt_ts = format_timestamp(ex["prompt_time"])
        model_name = ex.get("model", DEFAULT_MODEL)
        prompt_text = ex["prompt"]

        lines.append(f"[LOG_ENTRY type=PROMPT num={idx} session={short_id}]")
        lines.append(f"timestamp: {prompt_ts}")
        lines.append(f"model: {model_name}")
        lines.append("")
        lines.append(prompt_text)
        lines.append("")
        lines.append("")

        if ex["response"]:
            resp_ts = format_timestamp(ex["response_time"] or ex["prompt_time"])
            resp_text = ex["response"]
            lines.append(f"[LOG_ENTRY type=RESPONSE num={idx} session={short_id}]")
            lines.append(f"timestamp: {resp_ts}")
            lines.append(f"model: {model_name}")
            lines.append("")
            lines.append(resp_text)
            lines.append("")
            lines.append("")

    content = "\n".join(lines)
    
    # Check if existing content matches to avoid needless disk rewrites
    if log_path.exists():
        existing = log_path.read_text(encoding="utf-8")
        if existing == content:
            return log_path

    log_path.write_text(content, encoding="utf-8")
    print(f"Updated log: {log_path.name}")
    return log_path

def sync_all():
    """Find and sync all relevant transcripts."""
    transcripts = glob.glob(str(BRAIN_DIR / "*" / ".system_generated" / "logs" / "transcript_full.jsonl"))
    synced = []
    for t_path_str in transcripts:
        t_path = Path(t_path_str)
        if is_workspace_transcript(t_path):
            data = parse_transcript(t_path)
            if data:
                log_file = write_session_log(data)
                synced.append(log_file)
    return synced

def watch_loop():
    """Continuously poll for updates and keep logs synchronized."""
    print("Agent capture daemon started. Watching for conversation updates...")
    while True:
        try:
            sync_all()
        except Exception as e:
            print(f"Error in sync: {e}", file=sys.stderr)
        time.sleep(2)

def handle_hook():
    """Handle Antigravity or Claude Code lifecycle hook stdin."""
    try:
        raw_input = sys.stdin.read()
        if raw_input:
            data = json.loads(raw_input)
            t_path = data.get("transcriptPath")
            if t_path and Path(t_path).exists():
                session_data = parse_transcript(Path(t_path))
                if session_data:
                    write_session_log(session_data)
    except Exception:
        pass
    finally:
        sync_all()
        # Output valid JSON response for the hook contract
        print(json.dumps({"decision": "allow", "injectSteps": []}))

if __name__ == "__main__":
    if "--watch" in sys.argv:
        watch_loop()
    elif "--hook" in sys.argv or "--claude" in sys.argv:
        handle_hook()
    else:
        synced = sync_all()
        print(f"Synced {len(synced)} session logs.")
