# 8x Assignment Agent Capture Rule

- AUTOMATIC LOGGING: All prompts and model responses must be recorded in `.agent-logs/` matching the 8x assignment format.
- On every prompt and turn, the agent capture script `python3 scripts/agent_capture.py` runs to sync transcripts from `~/.gemini/antigravity-ide/brain/` into `.agent-logs/`.
- The background daemon `python3 scripts/agent_capture.py --watch` continuously monitors sessions.
- `.agent-logs/` must be committed to git alongside code changes.
