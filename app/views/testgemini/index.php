<?php require_once 'app/views/templates/header.php'; ?>
<div class="container mt-5">
  <h2> Gemini AI Prompt Tester</h2>

  <form method="POST" class="mb-4">
    <div class="mb-3">
      <label for="prompt" class="form-label">Enter a prompt:</label>
      <textarea name="prompt" id="prompt" rows="3" class="form-control" required>Give a short movie review for The Matrix</textarea>
    </div>
    <button class="btn btn-primary">Run Gemini</button>
  </form>

  <?php if (!empty($result)): ?>
    <h4>Gemini Response</h4>
    <div class="alert alert-info" style="white-space: pre-wrap;"><?= htmlspecialchars($result) ?></div>
  <?php endif; ?>
</div>

<?php
error_log("User submitted prompt: " . ($_POST['prompt'] ?? 'none'));
error_log("Gemini response: " . print_r($result, true));
?>
<?php require_once 'app/views/templates/footer.php'; ?>
