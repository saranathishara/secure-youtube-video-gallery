<?php
// Helper function to encrypt video IDs using base64
function encryptVideoId($videoId) {
    // Base64 encode for simplicity (consider using stronger encryption for higher security)
    return base64_encode($videoId);
}

// Array of YouTube video IDs
$videos = [
    encryptVideoId("dQw4w9WgXcQ"),
    encryptVideoId("kXYiU_JCYtU"),
    encryptVideoId("eVTXPUF4Oz4"),
    encryptVideoId("hTWKbfoikeg"),
    encryptVideoId("3JZ_D3ELwOQ"),
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Secure Video Gallery</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .video-thumbnail {
            cursor: pointer;
            transition: transform 0.2s;
        }
        .video-thumbnail:hover {
            transform: scale(1.05);
        }
        .no-js-message {
            display: none;
            text-align: center;
            padding: 20px;
            font-size: 1.2em;
            color: red;
        }
        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
            background: transparent;
        }
        .full-screen-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 2;
            background-color: #333;
            color: #fff;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 3px;
        }
    </style>
    <noscript>
        <style>
            /* Hide the gallery if JavaScript is disabled */
            .gallery {
                display: none;
            }
            .no-js-message {
                display: block;
            }
        </style>
    </noscript>
</head>
<body oncontextmenu="return false;"> <!-- Disable right-click on page -->

<div class="container my-5 gallery">
    <h1 class="text-center mb-4">Video Gallery</h1>
    <div class="row">
        <?php foreach ($videos as $encryptedVideo): ?>
            <div class="col-md-4 mb-4">
                <div class="card video-thumbnail" data-toggle="modal" data-target="#videoModal" data-encrypted-id="<?php echo $encryptedVideo; ?>">
                    <div class="thumbnail-container" style="display: none;">
                        <!-- Thumbnail loaded by JavaScript only -->
                    </div>
                    <div class="card-body">
                        <h5 class="card-title text-center">Video Title</h5>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Message for users with JavaScript disabled -->
<noscript>
    <div class="no-js-message">
        <p>This page requires JavaScript to display videos. Please enable JavaScript in your browser settings.</p>
    </div>
</noscript>

<!-- Modal -->
<div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content position-relative">
            <div class="modal-header">
                <h5 class="modal-title" id="videoModalLabel">Video Player</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body position-relative">
                <!-- Full-Screen Button -->
                <button class="full-screen-btn" onclick="toggleFullScreen()">Full Screen</button>
                <!-- Transparent overlay to block interactions -->
                <div class="overlay"></div>
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe id="videoFrame" class="embed-responsive-item" src="" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    // JavaScript is enabled - load video thumbnails
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.card.video-thumbnail').forEach(function(card) {
            var encryptedId = card.getAttribute('data-encrypted-id');
            var videoId = atob(encryptedId);  // Decrypt using base64
            var img = document.createElement('img');
            img.src = "https://img.youtube.com/vi/" + videoId + "/hqdefault.jpg";
            img.classList.add('card-img-top');
            img.alt = "Video Thumbnail";
            card.querySelector('.thumbnail-container').appendChild(img);
            card.querySelector('.thumbnail-container').style.display = 'block';
        });
    });

    // Disable Right-click and Developer Tools shortcuts
    document.addEventListener('contextmenu', function(e) {
        e.preventDefault();
    });
    document.onkeydown = function(e) {
        if (e.ctrlKey && (e.key === 'u' || e.key === 'U' || e.key === 's' || e.key === 'S')) {
            return false;
        }
        if (e.ctrlKey && e.shiftKey && (e.key === 'i' || e.key === 'I' || e.key === 'c' || e.key === 'j' || e.key === 'J')) {
            return false;
        }
    };

    // Video modal handling
    $('#videoModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var encryptedId = button.data('encrypted-id');
        var videoId = atob(encryptedId); // Decrypt video ID from base64
        var videoSrc = "https://www.youtube.com/embed/" + videoId + "?autoplay=1&rel=0";
        $('#videoFrame').attr('src', videoSrc);
    });

    $('#videoModal').on('hide.bs.modal', function () {
        $('#videoFrame').attr('src', '');
    });

    // Full-Screen Toggle
    function toggleFullScreen() {
        const modalContent = document.querySelector('.modal-content');
        if (!document.fullscreenElement) {
            modalContent.requestFullscreen().catch(err => console.error(err));
        } else {
            document.exitFullscreen();
        }
    }

    // Prevent Right-Click in Full-Screen Mode
    document.addEventListener('fullscreenchange', () => {
        if (document.fullscreenElement) {
            // Disable right-click in full-screen
            document.addEventListener('contextmenu', disableRightClick);
        } else {
            // Enable right-click when not in full-screen
            document.removeEventListener('contextmenu', disableRightClick);
        }
    });

    function disableRightClick(e) {
        e.preventDefault();
    }
</script>
</body>
</html>
