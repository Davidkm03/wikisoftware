<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $document->title }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            padding: 40px;
        }
        .header {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #4F46E5;
        }
        .header h1 {
            font-size: 24px;
            color: #1F2937;
            margin-bottom: 10px;
        }
        .meta {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .meta-row {
            display: table-row;
        }
        .meta-label {
            display: table-cell;
            width: 120px;
            font-weight: bold;
            color: #6B7280;
            padding: 5px 10px 5px 0;
        }
        .meta-value {
            display: table-cell;
            color: #1F2937;
            padding: 5px 0;
        }
        .category-badge {
            display: inline-block;
            padding: 4px 12px;
            background-color: #EEF2FF;
            color: #4F46E5;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            background-color: #D1FAE5;
            color: #065F46;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
        }
        .tags {
            margin: 15px 0;
        }
        .tag {
            display: inline-block;
            padding: 3px 10px;
            background-color: #F3F4F6;
            color: #4B5563;
            border-radius: 8px;
            font-size: 10px;
            margin-right: 5px;
            margin-bottom: 5px;
        }
        .content {
            margin: 30px 0;
            padding: 20px;
            background-color: #F9FAFB;
            border-left: 4px solid #4F46E5;
        }
        .content h2, .content h3, .content h4 {
            color: #1F2937;
            margin-top: 20px;
            margin-bottom: 10px;
        }
        .content h2 {
            font-size: 18px;
        }
        .content h3 {
            font-size: 16px;
        }
        .content h4 {
            font-size: 14px;
        }
        .content p {
            margin-bottom: 10px;
        }
        .content ul, .content ol {
            margin-left: 20px;
            margin-bottom: 10px;
        }
        .content li {
            margin-bottom: 5px;
        }
        .content a {
            color: #4F46E5;
            text-decoration: none;
        }
        .content code {
            background-color: #E5E7EB;
            padding: 2px 6px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            font-size: 11px;
        }
        .content pre {
            background-color: #1F2937;
            color: #F9FAFB;
            padding: 15px;
            border-radius: 8px;
            overflow-x: auto;
            margin: 15px 0;
        }
        .content pre code {
            background-color: transparent;
            color: #F9FAFB;
            padding: 0;
        }
        .content blockquote {
            border-left: 4px solid #D1D5DB;
            padding-left: 15px;
            margin: 15px 0;
            color: #6B7280;
            font-style: italic;
        }
        .content table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        .content table th,
        .content table td {
            border: 1px solid #D1D5DB;
            padding: 8px;
            text-align: left;
        }
        .content table th {
            background-color: #F3F4F6;
            font-weight: 600;
        }
        .attachments {
            margin-top: 30px;
            padding: 20px;
            background-color: #FFFBEB;
            border-left: 4px solid #F59E0B;
        }
        .attachments h3 {
            color: #92400E;
            font-size: 16px;
            margin-bottom: 10px;
        }
        .attachment-item {
            padding: 8px 0;
            border-bottom: 1px solid #FDE68A;
        }
        .attachment-item:last-child {
            border-bottom: none;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #E5E7EB;
            text-align: center;
            font-size: 10px;
            color: #9CA3AF;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>{{ $document->title }}</h1>
    </div>

    <!-- Metadata -->
    <div class="meta">
        <div class="meta-row">
            <div class="meta-label">Category:</div>
            <div class="meta-value">
                <span class="category-badge">{{ $document->category->icon ?? '📄' }} {{ $document->category->name }}</span>
            </div>
        </div>
        <div class="meta-row">
            <div class="meta-label">Status:</div>
            <div class="meta-value">
                <span class="status-badge">{{ ucfirst($document->status) }}</span>
            </div>
        </div>
        <div class="meta-row">
            <div class="meta-label">Author:</div>
            <div class="meta-value">{{ $document->author->name }}</div>
        </div>
        <div class="meta-row">
            <div class="meta-label">Created:</div>
            <div class="meta-value">{{ $document->created_at->format('F d, Y') }}</div>
        </div>
        <div class="meta-row">
            <div class="meta-label">Last Updated:</div>
            <div class="meta-value">{{ $document->updated_at->format('F d, Y h:i A') }}</div>
        </div>
        @if($document->tags->count() > 0)
        <div class="meta-row">
            <div class="meta-label">Tags:</div>
            <div class="meta-value">
                <div class="tags">
                    @foreach($document->tags as $tag)
                        <span class="tag">#{{ $tag->name }}</span>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Content -->
    <div class="content">
        {!! $document->content !!}
    </div>

    <!-- Attachments -->
    @if($document->attachments->count() > 0)
    <div class="attachments">
        <h3>📎 Attachments ({{ $document->attachments->count() }})</h3>
        @foreach($document->attachments as $attachment)
        <div class="attachment-item">
            <strong>{{ $attachment->original_name }}</strong>
            ({{ number_format($attachment->file_size / 1024, 2) }} KB)
        </div>
        @endforeach
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>Generated from Software Wiki on {{ now()->format('F d, Y h:i A') }}</p>
        <p>Document ID: {{ $document->id }} | Version: {{ $document->versions->count() }}</p>
    </div>
</body>
</html>
