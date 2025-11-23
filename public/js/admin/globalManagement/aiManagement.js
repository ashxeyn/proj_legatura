// AI Management Interactive JavaScript

document.addEventListener('DOMContentLoaded', function() {
  // Modal elements
  const aiAnalysisModal = document.getElementById('aiAnalysisModal');
  const closeAiAnalysisModal = document.getElementById('closeAiAnalysisModal');
  const reanalyzeConfirmModal = document.getElementById('reanalyzeConfirmModal');
  const implementedConfirmModal = document.getElementById('implementedConfirmModal');
  const deleteAiProjectModal = document.getElementById('deleteAiProjectModal');
  const deleteActivityModal = document.getElementById('deleteActivityModal');

  // Buttons
  const viewAiBtns = document.querySelectorAll('.view-ai-btn');
  const deleteAiBtns = document.querySelectorAll('.delete-ai-btn');
  const deleteActivityBtns = document.querySelectorAll('.delete-activity-btn');
  const reanalyzeBtn = document.getElementById('reanalyzeBtn');
  const implementedBtn = document.getElementById('implementedBtn');
  const cancelReanalyze = document.getElementById('cancelReanalyze');
  const confirmReanalyze = document.getElementById('confirmReanalyze');
  const cancelImplemented = document.getElementById('cancelImplemented');
  const confirmImplemented = document.getElementById('confirmImplemented');
  const cancelDeleteAi = document.getElementById('cancelDeleteAi');
  const confirmDeleteAi = document.getElementById('confirmDeleteAi');
  const cancelDeleteActivity = document.getElementById('cancelDeleteActivity');
  const confirmDeleteActivity = document.getElementById('confirmDeleteActivity');

  // Open AI Analysis Modal
  viewAiBtns.forEach(btn => {
    btn.addEventListener('click', function() {
      const projectId = this.dataset.projectId;
      const title = this.dataset.title;
      const status = this.dataset.status;
      const owner = this.dataset.owner;
      const contractor = this.dataset.contractor;
      const completion = this.dataset.completion;
      const progress = this.dataset.progress;
      const variance = this.dataset.variance;
      const risk = this.dataset.risk;
      const confidence = this.dataset.confidence;
      const recommendation = this.dataset.recommendation;

      // Populate modal
      document.getElementById('aiModalProjectId').textContent = projectId;
      document.getElementById('aiModalTitle').textContent = title;
      document.getElementById('aiModalStatus').textContent = status;
      document.getElementById('aiModalOwner').textContent = owner;
      document.getElementById('aiModalContractor').textContent = contractor;
      document.getElementById('aiModalCompletion').textContent = completion;
      document.getElementById('aiModalProgress').textContent = progress;
      document.getElementById('aiModalVariance').textContent = variance;
      document.getElementById('aiModalRisk').textContent = risk;
      document.getElementById('aiModalConfidence').textContent = confidence;
      document.getElementById('aiModalRecommendation').textContent = `"${recommendation}"`;

      // Update status badge color
      const statusBadge = document.getElementById('aiModalStatus');
      statusBadge.className = 'ml-2 inline-block px-3 py-1 rounded-full text-xs font-semibold';
      if (status.toLowerCase().includes('pending')) {
        statusBadge.classList.add('bg-yellow-100', 'text-yellow-700');
      } else if (status.toLowerCase().includes('acknowledge')) {
        statusBadge.classList.add('bg-blue-100', 'text-blue-700');
      } else {
        statusBadge.classList.add('bg-green-100', 'text-green-700');
      }

      aiAnalysisModal.classList.remove('hidden');
      aiAnalysisModal.classList.add('flex');
    });
  });

  // Close AI Analysis Modal
  closeAiAnalysisModal.addEventListener('click', function() {
    aiAnalysisModal.classList.add('hidden');
    aiAnalysisModal.classList.remove('flex');
  });

  // Close modal on outside click
  aiAnalysisModal.addEventListener('click', function(e) {
    if (e.target === aiAnalysisModal) {
      aiAnalysisModal.classList.add('hidden');
      aiAnalysisModal.classList.remove('flex');
    }
  });

  // Open Re-analyze Confirmation Modal
  reanalyzeBtn.addEventListener('click', function() {
    aiAnalysisModal.classList.add('hidden');
    aiAnalysisModal.classList.remove('flex');
    reanalyzeConfirmModal.classList.remove('hidden');
    reanalyzeConfirmModal.classList.add('flex');
  });

  // Cancel Re-analyze
  cancelReanalyze.addEventListener('click', function() {
    reanalyzeConfirmModal.classList.add('hidden');
    reanalyzeConfirmModal.classList.remove('flex');
    aiAnalysisModal.classList.remove('hidden');
    aiAnalysisModal.classList.add('flex');
  });

  // Confirm Re-analyze
  confirmReanalyze.addEventListener('click', function() {
    const loader = this.querySelector('.loader');
    const span = this.querySelector('span:not(.loader)');
    loader.classList.remove('hidden');
    span.textContent = 'Processing...';
    this.disabled = true;

    // Simulate API call
    setTimeout(() => {
      reanalyzeConfirmModal.classList.add('hidden');
      reanalyzeConfirmModal.classList.remove('flex');
      showToast('Project re-analysis triggered successfully!', 'success');
      loader.classList.add('hidden');
      span.textContent = 'Confirm';
      this.disabled = false;
    }, 2000);
  });

  // Open Recommendation Implemented Confirmation Modal
  implementedBtn.addEventListener('click', function() {
    aiAnalysisModal.classList.add('hidden');
    aiAnalysisModal.classList.remove('flex');
    implementedConfirmModal.classList.remove('hidden');
    implementedConfirmModal.classList.add('flex');
  });

  // Cancel Recommendation Implemented
  cancelImplemented.addEventListener('click', function() {
    implementedConfirmModal.classList.add('hidden');
    implementedConfirmModal.classList.remove('flex');
    aiAnalysisModal.classList.remove('hidden');
    aiAnalysisModal.classList.add('flex');
  });

  // Confirm Recommendation Implemented
  confirmImplemented.addEventListener('click', function() {
    const loader = this.querySelector('.loader');
    const span = this.querySelector('span:not(.loader)');
    loader.classList.remove('hidden');
    span.textContent = 'Processing...';
    this.disabled = true;

    // Simulate API call
    setTimeout(() => {
      implementedConfirmModal.classList.add('hidden');
      implementedConfirmModal.classList.remove('flex');
      showToast('Recommendation marked as implemented successfully!', 'success');
      loader.classList.add('hidden');
      span.textContent = 'Confirm';
      this.disabled = false;
    }, 2000);
  });

  // Close confirmation modals on outside click
  reanalyzeConfirmModal.addEventListener('click', function(e) {
    if (e.target === reanalyzeConfirmModal) {
      reanalyzeConfirmModal.classList.add('hidden');
      reanalyzeConfirmModal.classList.remove('flex');
      aiAnalysisModal.classList.remove('hidden');
      aiAnalysisModal.classList.add('flex');
    }
  });

  implementedConfirmModal.addEventListener('click', function(e) {
    if (e.target === implementedConfirmModal) {
      implementedConfirmModal.classList.add('hidden');
      implementedConfirmModal.classList.remove('flex');
      aiAnalysisModal.classList.remove('hidden');
      aiAnalysisModal.classList.add('flex');
    }
  });

  // Open Delete AI Project Modal
  deleteAiBtns.forEach(btn => {
    btn.addEventListener('click', function() {
      const projectId = this.dataset.projectId;
      const title = this.dataset.title;
      document.getElementById('deleteProjectInfo').textContent = `${projectId} - ${title}`;
      deleteAiProjectModal.classList.remove('hidden');
      deleteAiProjectModal.classList.add('flex');
    });
  });

  // Cancel Delete AI Project
  cancelDeleteAi.addEventListener('click', function() {
    deleteAiProjectModal.classList.add('hidden');
    deleteAiProjectModal.classList.remove('flex');
  });

  // Confirm Delete AI Project
  confirmDeleteAi.addEventListener('click', function() {
    const loader = this.querySelector('.loader');
    const span = this.querySelector('span:not(.loader)');
    loader.classList.remove('hidden');
    span.textContent = 'Deleting...';
    this.disabled = true;

    // Simulate API call
    setTimeout(() => {
      deleteAiProjectModal.classList.add('hidden');
      deleteAiProjectModal.classList.remove('flex');
      showToast('AI analysis deleted successfully!', 'success');
      loader.classList.add('hidden');
      span.textContent = 'Delete';
      this.disabled = false;
    }, 2000);
  });

  // Close delete modal on outside click
  deleteAiProjectModal.addEventListener('click', function(e) {
    if (e.target === deleteAiProjectModal) {
      deleteAiProjectModal.classList.add('hidden');
      deleteAiProjectModal.classList.remove('flex');
    }
  });

  // Open Delete Activity Modal
  deleteActivityBtns.forEach(btn => {
    btn.addEventListener('click', function() {
      const activityDate = this.dataset.activityDate;
      const activityAction = this.dataset.activityAction;
      document.getElementById('deleteActivityInfo').textContent = `${activityDate} - ${activityAction}`;
      deleteActivityModal.classList.remove('hidden');
      deleteActivityModal.classList.add('flex');
    });
  });

  // Cancel Delete Activity
  cancelDeleteActivity.addEventListener('click', function() {
    deleteActivityModal.classList.add('hidden');
    deleteActivityModal.classList.remove('flex');
  });

  // Confirm Delete Activity
  confirmDeleteActivity.addEventListener('click', function() {
    const loader = this.querySelector('.loader');
    const span = this.querySelector('span:not(.loader)');
    loader.classList.remove('hidden');
    span.textContent = 'Deleting...';
    this.disabled = true;

    // Simulate API call
    setTimeout(() => {
      deleteActivityModal.classList.add('hidden');
      deleteActivityModal.classList.remove('flex');
      showToast('Activity record deleted successfully!', 'success');
      loader.classList.add('hidden');
      span.textContent = 'Delete';
      this.disabled = false;
    }, 2000);
  });

  // Close delete activity modal on outside click
  deleteActivityModal.addEventListener('click', function(e) {
    if (e.target === deleteActivityModal) {
      deleteActivityModal.classList.add('hidden');
      deleteActivityModal.classList.remove('flex');
    }
  });

  // ESC key to close modals
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      if (!aiAnalysisModal.classList.contains('hidden')) {
        aiAnalysisModal.classList.add('hidden');
        aiAnalysisModal.classList.remove('flex');
      }
      if (!reanalyzeConfirmModal.classList.contains('hidden')) {
        reanalyzeConfirmModal.classList.add('hidden');
        reanalyzeConfirmModal.classList.remove('flex');
        aiAnalysisModal.classList.remove('hidden');
        aiAnalysisModal.classList.add('flex');
      }
      if (!implementedConfirmModal.classList.contains('hidden')) {
        implementedConfirmModal.classList.add('hidden');
        implementedConfirmModal.classList.remove('flex');
        aiAnalysisModal.classList.remove('hidden');
        aiAnalysisModal.classList.add('flex');
      }
      if (!deleteAiProjectModal.classList.contains('hidden')) {
        deleteAiProjectModal.classList.add('hidden');
        deleteAiProjectModal.classList.remove('flex');
      }
      if (!deleteActivityModal.classList.contains('hidden')) {
        deleteActivityModal.classList.add('hidden');
        deleteActivityModal.classList.remove('flex');
      }
    }
  });

  // Toast notification function
  function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `fixed bottom-8 right-8 px-6 py-4 rounded-lg shadow-2xl text-white font-semibold z-50 animate-slide-up ${
      type === 'success' ? 'bg-green-500' : 'bg-red-500'
    }`;
    toast.textContent = message;
    document.body.appendChild(toast);

    setTimeout(() => {
      toast.classList.add('opacity-0', 'transition-opacity');
      setTimeout(() => toast.remove(), 300);
    }, 3000);
  }
});
