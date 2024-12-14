<div class="container mt-4">
  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-body-tertiary p-3 rounded-3 mb-5">
      <li class="breadcrumb-item"><a href="/" class="text-decoration-none">Home</a></li>
      <li class="breadcrumb-item"><a href="/pro/plans" class="text-decoration-none">Pro</a></li>
      <li class="breadcrumb-item active" aria-current="page">Subscribe</li>
    </ol>
  </nav>

  <div class="card px-4 py-4 shadow-sm bg-body-tertiary border-0 rounded-3 mb-4">
    <h2 class="h3 fw-bold text-body">Review Subscription</h2>
    <hr class="mb-4">

    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4">
      <div class="mb-3 mb-md-0">
        <p class="fs-5 mb-2">
          <strong>Price:</strong>
          <span class="text-body">₹<?= (lcfirst($plan) == 'monthly' ? '50' : '480') ?>/-</span>
        </p>
        <p class="fs-5 mb-0">
          <strong>Billing Cycle:</strong>
          <span class="badge bg-primary fs-6"><?= htmlspecialchars(ucfirst($plan)) ?></span>
        </p>
      </div>
      <button id="payButton" class="btn btn-lg btn-success">Pay Now</button>
    </div>

    <!-- Plan Benefits -->
    <h5 class="fw-semibold mt-3 text-body">Plan Benefits:</h5>
    <hr class="mt-2 mb-4">

    <ul class="list-unstyled fs-5 text-start">
      <li class="mb-2">
        <i class="bi bi-check-circle-fill text-success me-2 fs-5"></i>Extra 1.5GB for image storage
      </li>
      <li class="mb-2">
        <i class="bi bi-check-circle-fill text-success me-2 fs-5"></i>AI Caption Generator for post images
      </li>
      <li class="mb-2">
        <i class="bi bi-check-circle-fill text-success me-2 fs-5"></i>Scheduling Posts
      </li>
      <li class="mb-2">
        <i class="bi bi-check-circle-fill text-success me-2 fs-5"></i>Exclusive Filters and Effects
      </li>
      <li class="mb-2">
        <i class="bi bi-check-circle-fill text-success me-2 fs-5"></i>Post Collections in Organized Way
      </li>
    </ul>

  </div>
</div>