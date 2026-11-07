<!DOCTYPE html>
<html>
<body>
<svg viewBox="126 274 800 498">
    <path id="np" d="<?= file_get_contents('public/norm_path.txt') ?>" />
</svg>
<script>
const p = document.getElementById('np');
fetch('/save_bbox.php?data=' + encodeURIComponent(p.getTotalLength()));
</script>
</body>
</html>

