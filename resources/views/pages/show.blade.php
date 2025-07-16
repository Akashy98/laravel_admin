<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $page->title }} - AstroIndia</title>

    @if($page->meta_description)
        <meta name="description" content="{{ $page->meta_description }}">
    @endif

    @if($page->meta_keywords)
        <meta name="keywords" content="{{ $page->meta_keywords }}">
    @endif

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background: #fff;
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .page-container {
            background: #fff;
            width: 100vw;
            min-height: 100vh;
            max-width: none;
            margin: 0;
            border-radius: 0;
            overflow: hidden;
            padding: 2rem 1rem 2rem 1rem;
        }
        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 0.5rem;
            text-align: center;
        }
        .page-desc {
            color: #666;
            text-align: center;
            margin-bottom: 1.5rem;
            font-size: 1.1rem;
        }
        .page-content {
            padding: 0;
            line-height: 1.8;
        }
        .page-content h1, .page-content h2, .page-content h3 {
            color: #333;
            margin-top: 2rem;
            margin-bottom: 1rem;
        }
        .page-content h1 {
            font-size: 2rem;
            border-bottom: 2px solid #667eea;
            padding-bottom: 0.5rem;
        }
        .page-content h2 {
            font-size: 1.5rem;
            color: #667eea;
        }
        .page-content h3 {
            font-size: 1.25rem;
            color: #555;
        }
        .page-content p {
            margin-bottom: 1rem;
            color: #444;
        }
        .page-content ul, .page-content ol {
            margin-bottom: 1rem;
            padding-left: 2rem;
        }
        .page-content li {
            margin-bottom: 0.5rem;
        }
        .meta-info {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            font-size: 0.9rem;
            color: #666;
        }
        .page-footer {
            background: #f8f9fa;
            padding: 1.5rem 2rem;
            text-align: center;
            border-top: 1px solid #dee2e6;
            margin: 0 -2rem;
            border-radius: 0 0 15px 15px;
        }
        .back-link {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
        }
        .back-link:hover {
            color: #764ba2;
            text-decoration: underline;
        }
        @media (max-width: 768px) {
            .page-container {
                padding: 1rem 0.5rem 1rem 0.5rem;
            }
            .page-title {
                font-size: 1.5rem;
            }
            .page-desc {
                font-size: 1rem;
            }
            .page-footer {
                padding: 1rem 0.5rem;
                margin: 0 -0.5rem;
                border-radius: 0 0 10px 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="page-container">
            {{-- <div class="page-title">{{ $page->title }}</div> --}}
            {{-- @if($page->meta_description)
                <div class="page-desc">{{ $page->meta_description }}</div>
            @endif --}}
            <div class="page-content">
                @if($page->meta_keywords)
                    <div class="meta-info">
                        <strong>Keywords:</strong> {{ $page->meta_keywords }}
                    </div>
                @endif
                <div class="content">
                    {!! $page->content !!}
                </div>
            </div>
            {{-- <div class="page-footer">
                <a href="{{ url('/') }}" class="back-link">
                    <i class="fas fa-arrow-left me-2"></i>Back to Home
                </a>
                <div class="mt-2">
                    <small class="text-muted">
                        Last updated: {{ $page->updated_at->format('F d, Y') }}
                    </small>
                </div>
            </div> --}}
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
