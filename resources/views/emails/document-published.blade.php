<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Document Published</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 3px solid #4F46E5;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #4F46E5;
            margin: 0;
            font-size: 24px;
        }
        .content {
            margin: 20px 0;
        }
        .document-card {
            background-color: #F9FAFB;
            border-left: 4px solid #4F46E5;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .document-title {
            font-size: 20px;
            font-weight: bold;
            color: #1F2937;
            margin-bottom: 10px;
        }
        .document-meta {
            font-size: 14px;
            color: #6B7280;
            margin-bottom: 15px;
        }
        .document-excerpt {
            color: #4B5563;
            font-size: 14px;
            line-height: 1.5;
            margin-bottom: 15px;
        }
        .category-badge {
            display: inline-block;
            padding: 4px 12px;
            background-color: #EEF2FF;
            color: #4F46E5;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            margin-right: 8px;
        }
        .tag {
            display: inline-block;
            padding: 3px 10px;
            background-color: #F3F4F6;
            color: #4B5563;
            border-radius: 8px;
            font-size: 11px;
            margin-right: 5px;
            margin-top: 5px;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #4F46E5;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin-top: 15px;
        }
        .button:hover {
            background-color: #4338CA;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #E5E7EB;
            text-align: center;
            font-size: 12px;
            color: #9CA3AF;
        }
        .greeting {
            font-size: 16px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📚 Software Wiki</h1>
        </div>

        <div class="content">
            <p class="greeting">Hola {{ $user->name }},</p>
            <p>Un nuevo documento ha sido publicado en el Wiki:</p>

            <div class="document-card">
                <div class="document-title">{{ $document->title }}</div>

                <div class="document-meta">
                    <span class="category-badge">{{ $document->category->icon ?? '📄' }} {{ $document->category->name }}</span>
                    <br>
                    Por: <strong>{{ $document->author->name }}</strong> |
                    {{ $document->created_at->format('d/m/Y H:i') }}
                </div>

                @if($document->excerpt)
                <div class="document-excerpt">
                    {{ strip_tags($document->excerpt) }}
                </div>
                @endif

                @if($document->tags->count() > 0)
                <div>
                    @foreach($document->tags as $tag)
                        <span class="tag">#{{ $tag->name }}</span>
                    @endforeach
                </div>
                @endif

                <a href="{{ url(route('documents.show', $document, false)) }}" class="button">
                    Ver Documento Completo
                </a>
            </div>

            <p style="margin-top: 20px; font-size: 14px; color: #6B7280;">
                Este documento está disponible en la categoría <strong>{{ $document->category->name }}</strong>
                y tiene un tiempo estimado de lectura de <strong>{{ $document->reading_time }} minutos</strong>.
            </p>
        </div>

        <div class="footer">
            <p>Has recibido este email porque eres {{ $user->role === 'admin' ? 'administrador' : 'editor' }} del Wiki.</p>
            <p style="margin-top: 10px;">
                &copy; {{ date('Y') }} Software Wiki | Departamento de Software
            </p>
        </div>
    </div>
</body>
</html>
