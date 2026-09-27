export interface TranscriptCue {
    speaker: string;
    start: number;
    end: number;
    text: string;
}

export interface ActionItem {
    id: number;
    task: string;
    assignee: string;
    completed: boolean;
}

export interface HighlightItem {
    id: number;
    meeting_id: number;
    timestamp_seconds: number;
    label: string;
    note: string | null;
}

export interface MeetingListItem {
    id: number;
    title: string;
    duration_seconds: number;
    created_at: string;
    speakers: string[];
    speaker_count: number;
}

export interface PaginatedMeetings {
    data: MeetingListItem[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    links: Array<{
        url: string | null;
        label: string;
        active: boolean;
    }>;
}

export interface MeetingDetail {
    id: number;
    title: string;
    video_url: string | null;
    duration_seconds: number;
    created_at: string;
}
