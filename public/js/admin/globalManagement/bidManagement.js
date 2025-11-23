document.addEventListener('DOMContentLoaded', () => {
	// Under Evaluation modal elements
	const evalModal = document.getElementById('viewBidModal');
	const evalCloseBtn = document.getElementById('closeViewBidModal');

	// Under Evaluation field refs
	const bidderCompany = document.getElementById('bidderCompany');
	const bidderEmail = document.getElementById('bidderEmail');
	const projectTitle = document.getElementById('projectTitle');
	const proposedCost = document.getElementById('proposedCost');
	const startDate = document.getElementById('startDate');
	const endDate = document.getElementById('endDate');
	const bidDescription = document.getElementById('bidDescription');

	// Approved modal elements
	const approvedModal = document.getElementById('approvedBidModal');
	const approvedCloseBtn = document.getElementById('closeApprovedBidModal');

	// Approved field refs
	const approvedReviewedBy = document.getElementById('approvedReviewedBy');
	const approvedDateAction = document.getElementById('approvedDateAction');
	const approvedRemarks = document.getElementById('approvedRemarks');
	const approvedBidderCompany = document.getElementById('approvedBidderCompany');
	const approvedBidderEmail = document.getElementById('approvedBidderEmail');
	const approvedProjectTitle = document.getElementById('approvedProjectTitle');
	const approvedProposedCost = document.getElementById('approvedProposedCost');
	const approvedStartDate = document.getElementById('approvedStartDate');
	const approvedEndDate = document.getElementById('approvedEndDate');
	const approvedBidDescription = document.getElementById('approvedBidDescription');

	// Rejected modal elements
	const rejectedModal = document.getElementById('rejectedBidModal');
	const rejectedCloseBtn = document.getElementById('closeRejectedBidModal');

	// Rejected field refs
	const rejectedReviewedBy = document.getElementById('rejectedReviewedBy');
	const rejectedDateAction = document.getElementById('rejectedDateAction');
	const rejectedRemarks = document.getElementById('rejectedRemarks');
	const rejectedBidderCompany = document.getElementById('rejectedBidderCompany');
	const rejectedBidderEmail = document.getElementById('rejectedBidderEmail');
	const rejectedProjectTitle = document.getElementById('rejectedProjectTitle');
	const rejectedProposedCost = document.getElementById('rejectedProposedCost');
	const rejectedStartDate = document.getElementById('rejectedStartDate');
	const rejectedEndDate = document.getElementById('rejectedEndDate');
	const rejectedDuration = document.getElementById('rejectedDuration');
	const rejectedBidDescription = document.getElementById('rejectedBidDescription');

	// Edit modal elements
	const editModal = document.getElementById('editBidModal');
	const editCloseBtn = document.getElementById('closeEditBidModal');
	const cancelEditBtn = document.getElementById('cancelEditBtn');
	const saveChangesBtn = document.getElementById('saveChangesBtn');

	// Edit field refs
	const editBidId = document.getElementById('editBidId');
	const editCurrentStatus = document.getElementById('editCurrentStatus');
	const editBidderCompany = document.getElementById('editBidderCompany');
	const editBidderEmail = document.getElementById('editBidderEmail');
	const editProjectTitle = document.getElementById('editProjectTitle');
	const editPropertyType = document.getElementById('editPropertyType');
	const editProposedCost = document.getElementById('editProposedCost');
	const editStartDate = document.getElementById('editStartDate');
	const editEndDate = document.getElementById('editEndDate');
	const editStatus = document.getElementById('editStatus');
	const editBidDescription = document.getElementById('editBidDescription');

	// Save confirmation modal elements
	const saveConfirmModal = document.getElementById('saveConfirmModal');
	const cancelSaveBtn = document.getElementById('cancelSaveBtn');
	const confirmSaveBtn = document.getElementById('confirmSaveBtn');

	// Delete modal elements
	const deleteModal = document.getElementById('deleteBidModal');
	const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
	const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

	// Delete field refs
	const deleteBidId = document.getElementById('deleteBidId');
	const deleteProjectTitle = document.getElementById('deleteProjectTitle');
	const deleteContractor = document.getElementById('deleteContractor');

	function formatPHP(value) {
		if (value === null || value === undefined) return '';
		const num = typeof value === 'number' ? value : Number(String(value).replace(/[^0-9.-]/g, ''));
		if (Number.isNaN(num)) return '';
		return num.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
	}

	function calculateDuration(startDateStr, endDateStr) {
		if (!startDateStr || !endDateStr) return 'mm / dd / yy';
		const start = new Date(startDateStr);
		const end = new Date(endDateStr);
		const diffTime = Math.abs(end - start);
		const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
		
		const months = Math.floor(diffDays / 30);
		const days = diffDays % 30;
		return `${months} months / ${days} days`;
	}

	function open(el) {
		if (!el) return;
		el.classList.remove('hidden');
		el.classList.add('flex');
	}

	function close(el) {
		if (!el) return;
		el.classList.remove('flex');
		el.classList.add('hidden');
	}

	// Delegate View clicks
	document.addEventListener('click', (e) => {
		const btn = e.target.closest('.action-btn--view');
		if (!btn) return;

		const tr = btn.closest('tr');
		if (!tr) return;
		const statusEl = tr.querySelector('td:nth-child(6) span');
		const status = (statusEl?.dataset?.status || statusEl?.textContent || '').trim();

		if (status === 'Under Evaluation') {
			// Populate Under Evaluation modal fields
			bidderCompany && (bidderCompany.textContent = btn.dataset.contractor || '-');
			bidderEmail && (bidderEmail.textContent = btn.dataset.email || '-');
			projectTitle && (projectTitle.textContent = btn.dataset.project || '-');
			proposedCost && (proposedCost.value = formatPHP(btn.dataset.proposedCost || ''));
			startDate && (startDate.value = btn.dataset.startDate || '');
			endDate && (endDate.value = btn.dataset.endDate || '');
			bidDescription && (bidDescription.value = btn.dataset.description || '');

			open(evalModal);
			return;
		}

		if (status === 'Approved') {
			// Populate Approved modal fields
			approvedReviewedBy && (approvedReviewedBy.textContent = btn.dataset.reviewedBy || '-');
			approvedDateAction && (approvedDateAction.textContent = btn.dataset.dateAction || '-');
			approvedRemarks && (approvedRemarks.value = btn.dataset.remarks || '');

			approvedBidderCompany && (approvedBidderCompany.textContent = btn.dataset.contractor || '-');
			approvedBidderEmail && (approvedBidderEmail.textContent = btn.dataset.email || '-');
			approvedProjectTitle && (approvedProjectTitle.textContent = btn.dataset.project || '-');

			approvedProposedCost && (approvedProposedCost.value = formatPHP(btn.dataset.proposedCost || ''));
			approvedStartDate && (approvedStartDate.value = btn.dataset.startDate || '');
			approvedEndDate && (approvedEndDate.value = btn.dataset.endDate || '');
			approvedBidDescription && (approvedBidDescription.value = btn.dataset.description || '');

			open(approvedModal);
			return;
		}

		if (status === 'Rejected') {
			// Populate Rejected modal fields
			rejectedReviewedBy && (rejectedReviewedBy.textContent = btn.dataset.reviewedBy || '-');
			rejectedDateAction && (rejectedDateAction.textContent = btn.dataset.dateAction || '-');
			rejectedRemarks && (rejectedRemarks.value = btn.dataset.remarks || '');

			rejectedBidderCompany && (rejectedBidderCompany.textContent = btn.dataset.contractor || '-');
			rejectedBidderEmail && (rejectedBidderEmail.textContent = btn.dataset.email || '-');
			rejectedProjectTitle && (rejectedProjectTitle.textContent = btn.dataset.project || '-');

			rejectedProposedCost && (rejectedProposedCost.value = formatPHP(btn.dataset.proposedCost || ''));
			rejectedStartDate && (rejectedStartDate.value = btn.dataset.startDate || '');
			rejectedEndDate && (rejectedEndDate.value = btn.dataset.endDate || '');
			rejectedDuration && (rejectedDuration.textContent = calculateDuration(btn.dataset.startDate, btn.dataset.endDate));
			rejectedBidDescription && (rejectedBidDescription.value = btn.dataset.description || '');

			open(rejectedModal);
			return;
		}
	});

	// Delegate Edit clicks
	document.addEventListener('click', (e) => {
		const btn = e.target.closest('.action-btn--edit');
		if (!btn) return;

		// Populate Edit modal fields from data attributes
		editBidId && (editBidId.textContent = btn.dataset.bidId || '-');
		editCurrentStatus && (editCurrentStatus.textContent = btn.dataset.status || '-');
		editBidderCompany && (editBidderCompany.value = btn.dataset.contractor || '');
		editBidderEmail && (editBidderEmail.value = btn.dataset.email || '');
		editProjectTitle && (editProjectTitle.value = btn.dataset.project || '');
		editProposedCost && (editProposedCost.value = formatPHP(btn.dataset.proposedCost || ''));
		editStartDate && (editStartDate.value = btn.dataset.startDate || '');
		editEndDate && (editEndDate.value = btn.dataset.endDate || '');
		editStatus && (editStatus.value = btn.dataset.status || 'Under Evaluation');
		editBidDescription && (editBidDescription.value = btn.dataset.description || '');

		open(editModal);
	});

	// Close interactions for Edit modal
	editCloseBtn && editCloseBtn.addEventListener('click', () => close(editModal));
	cancelEditBtn && cancelEditBtn.addEventListener('click', () => close(editModal));
	editModal && editModal.addEventListener('click', (e) => {
		if (e.target === editModal) close(editModal);
	});

	// Save Changes button - open confirmation modal
	saveChangesBtn && saveChangesBtn.addEventListener('click', () => {
		open(saveConfirmModal);
	});

	// Save confirmation modal interactions
	cancelSaveBtn && cancelSaveBtn.addEventListener('click', () => {
		close(saveConfirmModal);
	});

	confirmSaveBtn && confirmSaveBtn.addEventListener('click', () => {
		// TODO: Implement actual save logic (API call, form submission, etc.)
		console.log('Saving bid changes...');
		// For now, just close both modals
		close(saveConfirmModal);
		close(editModal);
		// You could show a success notification here
		alert('Bid changes saved successfully!');
	});

	saveConfirmModal && saveConfirmModal.addEventListener('click', (e) => {
		if (e.target === saveConfirmModal) close(saveConfirmModal);
	});

	// Delegate Delete clicks
	document.addEventListener('click', (e) => {
		const btn = e.target.closest('.action-btn--delete');
		if (!btn) return;

		// Populate Delete modal fields from data attributes
		deleteBidId && (deleteBidId.textContent = btn.dataset.bidId || '-');
		deleteProjectTitle && (deleteProjectTitle.textContent = btn.dataset.project || '-');
		deleteContractor && (deleteContractor.textContent = btn.dataset.contractor || '-');

		open(deleteModal);
	});

	// Delete modal interactions
	cancelDeleteBtn && cancelDeleteBtn.addEventListener('click', () => {
		close(deleteModal);
	});

	confirmDeleteBtn && confirmDeleteBtn.addEventListener('click', () => {
		// TODO: Implement actual delete logic (API call, etc.)
		console.log('Deleting bid...');
		// For now, just close the modal
		close(deleteModal);
		// You could show a success notification here
		alert('Bid deleted successfully!');
	});

	deleteModal && deleteModal.addEventListener('click', (e) => {
		if (e.target === deleteModal) close(deleteModal);
	});

	// Close interactions for Under Evaluation modal
	evalCloseBtn && evalCloseBtn.addEventListener('click', () => close(evalModal));
	evalModal && evalModal.addEventListener('click', (e) => {
		if (e.target === evalModal) close(evalModal);
	});

	// Close interactions for Approved modal
	approvedCloseBtn && approvedCloseBtn.addEventListener('click', () => close(approvedModal));
	approvedModal && approvedModal.addEventListener('click', (e) => {
		if (e.target === approvedModal) close(approvedModal);
	});

	// Close interactions for Rejected modal
	rejectedCloseBtn && rejectedCloseBtn.addEventListener('click', () => close(rejectedModal));
	rejectedModal && rejectedModal.addEventListener('click', (e) => {
		if (e.target === rejectedModal) close(rejectedModal);
	});

	// ESC to close whichever is open
	document.addEventListener('keydown', (e) => {
		if (e.key === 'Escape') {
			if (evalModal && evalModal.classList.contains('flex')) close(evalModal);
			if (approvedModal && approvedModal.classList.contains('flex')) close(approvedModal);
			if (rejectedModal && rejectedModal.classList.contains('flex')) close(rejectedModal);
			if (editModal && editModal.classList.contains('flex')) close(editModal);
			if (saveConfirmModal && saveConfirmModal.classList.contains('flex')) close(saveConfirmModal);
			if (deleteModal && deleteModal.classList.contains('flex')) close(deleteModal);
		}
	});

	// UX: format costs nicely on blur
	proposedCost && proposedCost.addEventListener('blur', () => {
		proposedCost.value = formatPHP(proposedCost.value);
	});
	approvedProposedCost && approvedProposedCost.addEventListener('blur', () => {
		approvedProposedCost.value = formatPHP(approvedProposedCost.value);
	});
	editProposedCost && editProposedCost.addEventListener('blur', () => {
		editProposedCost.value = formatPHP(editProposedCost.value);
	});
});

