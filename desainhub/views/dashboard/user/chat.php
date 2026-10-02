<?php
$currentUserId = $_SESSION['user_id'] ?? 0;
$currentUserRole = $_SESSION['user_role'] ?? 'customer';
?>
<div class="dh-chat-app">
    <div class="dh-chat-layout">
        <!-- Sidebar: Daftar Percakapan -->
        <div class="dh-chat-sidebar">
            <div class="dh-chat-sidebar-header">
                <h5 class="mb-0"><i class="bi bi-chat-dots-fill"></i> Percakapan</h5>
                <button class="btn btn-sm btn-primary dh-btn-new-chat" onclick="loadAvailableOrders()" title="Chat Baru">
                    <i class="bi bi-plus-lg"></i>
                </button>
            </div>
            <div class="dh-chat-search">
                <input type="text" class="form-control form-control-sm" placeholder="Cari percakapan..." id="chatSearchInput" oninput="filterConversations()">
            </div>
            <div class="dh-chat-conversation-list" id="conversationList">
                <div class="text-center text-muted py-4">
                    <div class="spinner-border spinner-border-sm" role="status"></div>
                    <p class="mt-2 mb-0 small">Memuat percakapan...</p>
                </div>
            </div>
        </div>

        <!-- Area Chat Utama -->
        <div class="dh-chat-main" id="chatMain">
            <div class="dh-chat-placeholder">
                <div class="text-center">
                    <i class="bi bi-chat-square-text" style="font-size:4rem;color:var(--dh-primary);opacity:0.5;"></i>
                    <h5 class="mt-3 text-muted">Pilih percakapan</h5>
                    <p class="text-muted small">Pilih order di sidebar untuk mulai chat dengan designer</p>
                </div>
            </div>
        </div>

        <!-- Detail Info (opsional, tampil saat chat terbuka) -->
        <div class="dh-chat-detail" id="chatDetail" style="display:none;">
            <div class="p-3">
                <h6 class="mb-3">Info Order</h6>
                <div id="chatDetailContent"></div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Pilih Order untuk Chat Baru -->
<div class="modal fade" id="newChatModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-plus-circle"></i> Chat Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small">Pilih order yang ingin Anda diskusikan:</p>
                <div id="availableOrdersList" class="list-group list-group-flush" style="max-height:350px;overflow-y:auto;">
                    <div class="text-center py-3 text-muted small">Memuat daftar order...</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Template: Item Percakapan -->
<template id="conversationItemTpl">
    <div class="dh-chat-conversation-item" data-order-id="" onclick="openChat(this)" role="button">
        <div class="dh-chat-avatar">
            <span class="dh-chat-avatar-text"></span>
        </div>
        <div class="dh-chat-conv-info">
            <div class="dh-chat-conv-name"></div>
            <div class="dh-chat-conv-preview"></div>
        </div>
        <div class="dh-chat-conv-meta">
            <div class="dh-chat-conv-time"></div>
            <span class="badge bg-danger dh-chat-unread" style="display:none;">0</span>
        </div>
    </div>
</template>

<!-- Template: Pesan -->
<template id="messageBubbleTpl">
    <div class="dh-chat-bubble">
        <div class="dh-chat-bubble-content">
            <div class="dh-chat-bubble-text"></div>
            <div class="dh-chat-bubble-attachment"></div>
        </div>
        <div class="dh-chat-bubble-time"></div>
    </div>
</template>

<style>
.dh-chat-app { height: calc(100vh - 140px); }
.dh-chat-layout { display: flex; height: 100%; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.08); }

/* Sidebar */
.dh-chat-sidebar { width: 320px; min-width: 320px; border-right: 1px solid #e9ecef; display: flex; flex-direction: column; background: #fafbfc; }
.dh-chat-sidebar-header { padding: 16px 20px; border-bottom: 1px solid #e9ecef; display: flex; justify-content: space-between; align-items: center; background: #fff; }
.dh-chat-search { padding: 12px 16px; border-bottom: 1px solid #e9ecef; }
.dh-chat-conversation-list { flex: 1; overflow-y: auto; }
.dh-chat-conversation-item {
    display: flex; align-items: center; gap: 12px;
    padding: 14px 16px; cursor: pointer; border-bottom: 1px solid #f0f0f0;
    transition: background 0.15s;
}
.dh-chat-conversation-item:hover, .dh-chat-conversation-item.active { background: #eef0ff; }
.dh-chat-avatar {
    width: 44px; height: 44px; border-radius: 50%; background: var(--dh-primary);
    display: flex; align-items: center; justify-content: center; color: #fff;
    font-weight: 600; font-size: 1rem; flex-shrink: 0;
}
.dh-chat-conv-info { flex: 1; min-width: 0; }
.dh-chat-conv-name { font-weight: 600; font-size: 0.875rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.dh-chat-conv-preview { font-size: 0.75rem; color: #6c757d; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-top: 2px; }
.dh-chat-conv-meta { text-align: right; flex-shrink: 0; }
.dh-chat-conv-time { font-size: 0.7rem; color: #adb5bd; }

/* Main Chat Area */
.dh-chat-main { flex: 1; display: flex; flex-direction: column; min-width: 0; background: #f8f9fa; }
.dh-chat-placeholder { flex: 1; display: flex; align-items: center; justify-content: center; }
.dh-chat-messages { flex: 1; overflow-y: auto; padding: 20px; display: flex; flex-direction: column; gap: 8px; }

/* Message Bubbles */
.dh-chat-bubble { max-width: 75%; margin-bottom: 4px; }
.dh-chat-bubble.me { align-self: flex-end; }
.dh-chat-bubble.other { align-self: flex-start; }
.dh-chat-bubble-content {
    padding: 10px 16px; border-radius: 16px; font-size: 0.875rem; line-height: 1.5;
}
.dh-chat-bubble.me .dh-chat-bubble-content { background: var(--dh-primary); color: #fff; border-bottom-right-radius: 4px; }
.dh-chat-bubble.other .dh-chat-bubble-content { background: #fff; color: #212529; border-bottom-left-radius: 4px; box-shadow: 0 1px 2px rgba(0,0,0,0.06); }
.dh-chat-bubble-time { font-size: 0.65rem; color: #adb5bd; margin-top: 2px; padding: 0 4px; }
.dh-chat-bubble.me .dh-chat-bubble-time { text-align: right; }
.dh-chat-bubble-attachment img { max-width: 200px; border-radius: 8px; margin-top: 6px; cursor: pointer; }
.dh-chat-bubble-attachment a { color: inherit; text-decoration: underline; font-size: 0.8rem; }
.dh-chat-bubble-sender { font-size: 0.7rem; font-weight: 600; color: var(--dh-primary); margin-bottom: 2px; padding: 0 4px; }

.dh-chat-header {
    padding: 14px 20px; background: #fff; border-bottom: 1px solid #e9ecef;
    display: flex; align-items: center; gap: 12px;
}
.dh-chat-header-back { display: none; cursor: pointer; font-size: 1.2rem; color: #6c757d; }
.dh-chat-header-info { flex: 1; }
.dh-chat-header-name { font-weight: 600; font-size: 0.95rem; }
.dh-chat-header-desc { font-size: 0.75rem; color: #6c757d; }

.dh-chat-input-area {
    padding: 16px 20px; background: #fff; border-top: 1px solid #e9ecef;
    display: flex; gap: 10px; align-items: flex-end;
}
.dh-chat-input-area textarea {
    flex: 1; border-radius: 20px; padding: 10px 16px; resize: none;
    border: 1px solid #dee2e6; font-size: 0.875rem; max-height: 100px;
}
.dh-chat-input-area textarea:focus { outline: none; border-color: var(--dh-primary); box-shadow: 0 0 0 2px rgba(108,99,255,0.15); }
.dh-chat-send-btn {
    width: 42px; height: 42px; border-radius: 50%; background: var(--dh-primary);
    color: #fff; border: none; display: flex; align-items: center; justify-content: center;
    cursor: pointer; transition: all 0.15s; flex-shrink: 0;
}
.dh-chat-send-btn:hover { background: #5a52d5; }
.dh-chat-send-btn:disabled { background: #c5c5f0; cursor: not-allowed; }

/* Detail Panel */
.dh-chat-detail { width: 260px; min-width: 260px; border-left: 1px solid #e9ecef; background: #fafbfc; overflow-y: auto; }

/* Responsive */
@media (max-width: 992px) {
    .dh-chat-sidebar { width: 280px; min-width: 280px; }
    .dh-chat-detail { display: none !important; }
}
@media (max-width: 768px) {
    .dh-chat-sidebar { width: 100%; min-width: 100%; }
    .dh-chat-main { display: none; }
    .dh-chat-main.open { display: flex; }
    .dh-chat-header-back { display: block; }
    .dh-chat-sidebar.hidden { display: none; }
}

.dh-chat-date-divider {
    text-align: center; font-size: 0.7rem; color: #adb5bd;
    margin: 16px 0 8px; text-transform: uppercase; letter-spacing: 0.5px;
}
</style>

<script>
let currentOrderId = null;
let currentReceiverId = null;
let currentOrderInfo = null;
let chatPollInterval = null;
let lastMessageId = 0;

// ============ Load Conversations ============
async function loadConversations() {
    try {
        const res = await fetch('<?= url("api/chat/conversations") ?>');
        const data = await res.json();
        renderConversations(data);
        updateUnreadBadge();
    } catch (err) {
        console.error('Gagal load percakapan:', err);
        document.getElementById('conversationList').innerHTML = '<div class="text-center text-danger py-4 small">Gagal memuat percakapan</div>';
    }
}

function renderConversations(conversations) {
    const list = document.getElementById('conversationList');
    const tpl = document.getElementById('conversationItemTpl');

    if (!conversations.length) {
        list.innerHTML = `<div class="text-center text-muted py-4">
            <i class="bi bi-chat-square" style="font-size:2rem;opacity:0.4;"></i>
            <p class="mt-2 mb-0 small">Belum ada percakapan</p>
            <button class="btn btn-sm btn-link" onclick="loadAvailableOrders()">Mulai chat baru</button>
        </div>`;
        return;
    }

    list.innerHTML = '';
    conversations.forEach(conv => {
        const item = tpl.content.cloneNode(true);
        const el = item.querySelector('.dh-chat-conversation-item');
        el.setAttribute('data-order-id', conv.order_id);
        el.setAttribute('data-receiver-id', conv.customer_id || conv.designer_id || 0);
        el.setAttribute('data-kode', conv.kode_order);
        el.setAttribute('data-status', conv.order_status);

        <?php if ($currentUserRole === 'customer'): ?>
        el.querySelector('.dh-chat-conv-name').textContent = conv.designer_nama || 'Designer';
        el.querySelector('.dh-chat-avatar-text').textContent = (conv.designer_nama || 'D').charAt(0).toUpperCase();
        <?php elseif ($currentUserRole === 'designer'): ?>
        el.querySelector('.dh-chat-conv-name').textContent = conv.customer_nama || 'Customer';
        el.querySelector('.dh-chat-avatar-text').textContent = (conv.customer_nama || 'C').charAt(0).toUpperCase();
        <?php else: ?>
        el.querySelector('.dh-chat-conv-name').textContent = `${conv.customer_nama || 'C'} ↔ ${conv.designer_nama || 'D'}`;
        el.querySelector('.dh-chat-avatar-text').textContent = (conv.customer_nama || 'A').charAt(0).toUpperCase();
        <?php endif; ?>

        el.querySelector('.dh-chat-conv-preview').textContent = conv.last_message || '';
        el.querySelector('.dh-chat-conv-time').textContent = formatChatTime(conv.last_time);

        const unreadBadge = el.querySelector('.dh-chat-unread');
        if (conv.unread > 0) {
            unreadBadge.style.display = 'inline-block';
            unreadBadge.textContent = conv.unread;
        }

        list.appendChild(el);
    });
}

function filterConversations() {
    const query = document.getElementById('chatSearchInput').value.toLowerCase();
    document.querySelectorAll('.dh-chat-conversation-item').forEach(item => {
        const name = item.querySelector('.dh-chat-conv-name').textContent.toLowerCase();
        const preview = item.querySelector('.dh-chat-conv-preview').textContent.toLowerCase();
        item.style.display = (name.includes(query) || preview.includes(query)) ? 'flex' : 'none';
    });
}

// ============ Open Chat ============
async function openChat(element) {
    const orderId = element.getAttribute('data-order-id');
    currentOrderId = orderId;
    currentReceiverId = element.getAttribute('data-receiver-id');
    currentOrderInfo = {
        kode: element.getAttribute('data-kode'),
        status: element.getAttribute('data-status'),
        partnerName: element.querySelector('.dh-chat-conv-name').textContent
    };

    // Highlight active conversation
    document.querySelectorAll('.dh-chat-conversation-item').forEach(item => item.classList.remove('active'));
    element.classList.add('active');

    // Reset unread badge
    const badge = element.querySelector('.dh-chat-unread');
    if (badge) { badge.style.display = 'none'; }

    // Tampilkan area chat
    const main = document.getElementById('chatMain');
    main.innerHTML = `
        <div class="dh-chat-header">
            <span class="dh-chat-header-back" onclick="closeChat()"><i class="bi bi-arrow-left"></i></span>
            <div class="dh-chat-header-info">
                <div class="dh-chat-header-name">${currentOrderInfo.partnerName}</div>
                <div class="dh-chat-header-desc">${currentOrderInfo.kode} · <span class="badge bg-<?= statusBadge('active') ?>">Loading...</span></div>
            </div>
        </div>
        <div class="dh-chat-messages" id="chatMessages">
            <div class="text-center py-4 text-muted small">Memuat pesan...</div>
        </div>
        <div class="dh-chat-input-area">
            <label class="btn btn-sm btn-outline-secondary mb-0" for="fileAttachment" style="border-radius:50%;width:36px;height:36px;display:flex;align-items:center;justify-content:center;padding:0;">
                <i class="bi bi-paperclip"></i>
            </label>
            <input type="file" id="fileAttachment" style="display:none;" onchange="handleFileSelect(this)">
            <textarea id="chatMessageInput" rows="1" placeholder="Ketik pesan..." onkeydown="handleChatKey(event)"></textarea>
            <button class="dh-chat-send-btn" onclick="sendMessage()" id="sendBtn" disabled><i class="bi bi-send-fill"></i></button>
        </div>
    `;

    // Mobile: sembunyikan sidebar
    if (window.innerWidth <= 768) {
        document.querySelector('.dh-chat-sidebar').classList.add('hidden');
        main.classList.add('open');
    }

    // Auto-resize textarea
    const textarea = document.getElementById('chatMessageInput');
    textarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 100) + 'px';
        document.getElementById('sendBtn').disabled = !this.value.trim();
    });

    // Update detail panel
    updateDetailPanel();

    // Load messages
    await loadMessages();

    // Start polling
    startPolling();
}

function closeChat() {
    currentOrderId = null;
    stopPolling();
    const main = document.getElementById('chatMain');
    main.innerHTML = `<div class="dh-chat-placeholder">
        <div class="text-center">
            <i class="bi bi-chat-square-text" style="font-size:4rem;color:var(--dh-primary);opacity:0.5;"></i>
            <h5 class="mt-3 text-muted">Pilih percakapan</h5>
            <p class="text-muted small">Pilih order di sidebar untuk mulai chat</p>
        </div>
    </div>`;
    main.classList.remove('open');
    document.querySelector('.dh-chat-sidebar').classList.remove('hidden');
    document.getElementById('chatDetail').style.display = 'none';
}

function updateDetailPanel() {
    const detail = document.getElementById('chatDetail');
    detail.style.display = 'block';
    document.getElementById('chatDetailContent').innerHTML = `
        <div class="mb-2"><strong>Order:</strong><br>${currentOrderInfo.kode}</div>
        <div class="mb-2"><strong>Status:</strong><br><span class="badge bg-${getStatusBadge(currentOrderInfo.status)}">${currentOrderInfo.status.replace(/_/g, ' ')}</span></div>
        <div class="mb-2"><strong>Partner:</strong><br>${currentOrderInfo.partnerName}</div>
    `;
}

function getStatusBadge(status) {
    const map = {
        'menunggu_pembayaran':'warning','dibayar':'info','brief_masuk':'info',
        'proses_desain':'primary','revisi':'warning','menunggu_approval':'primary',
        'selesai':'success','dibatalkan':'danger'
    };
    return map[status] || 'secondary';
}

// ============ Load Messages ============
async function loadMessages() {
    if (!currentOrderId) return;
    try {
        const res = await fetch(`<?= url("api/chat/messages") ?>/${currentOrderId}`);
        const messages = await res.json();
        renderMessages(messages);
        if (messages.length > 0) {
            lastMessageId = messages[messages.length - 1].id;
        }
        scrollToBottom();
    } catch (err) {
        console.error('Gagal load pesan:', err);
    }
}

function renderMessages(messages) {
    const container = document.getElementById('chatMessages');
    if (!container) return;

    if (!messages.length) {
        container.innerHTML = '<div class="text-center text-muted py-4 small">Belum ada pesan. Mulai percakapan!</div>';
        return;
    }

    const tpl = document.getElementById('messageBubbleTpl');
    const currentUserId = <?= $currentUserId ?>;
    let html = '';
    let lastDate = '';

    messages.forEach(msg => {
        const msgDate = msg.created_at ? msg.created_at.substring(0, 10) : '';
        if (msgDate !== lastDate) {
            html += `<div class="dh-chat-date-divider">${formatDateDivider(msg.created_at)}</div>`;
            lastDate = msgDate;
        }

        const isMe = msg.sender_id == currentUserId;
        const bubble = tpl.content.cloneNode(true);
        const wrapper = bubble.querySelector('.dh-chat-bubble');
        wrapper.classList.add(isMe ? 'me' : 'other');

        bubble.querySelector('.dh-chat-bubble-text').textContent = msg.pesan || '';

        if (!isMe && msg.sender_nama) {
            const senderEl = document.createElement('div');
            senderEl.className = 'dh-chat-bubble-sender';
            senderEl.textContent = msg.sender_nama + (msg.sender_role === 'admin' ? ' (Admin)' : '');
            wrapper.insertBefore(senderEl, wrapper.firstChild);
        }

        if (msg.lampiran) {
            const ext = msg.lampiran.split('.').pop().toLowerCase();
            const attachEl = bubble.querySelector('.dh-chat-bubble-attachment');
            if (['jpg','jpeg','png','gif','webp'].includes(ext)) {
                attachEl.innerHTML = `<img src="<?= url('assets/uploads/chat') ?>/${msg.lampiran}" alt="lampiran" onclick="window.open(this.src)">`;
            } else {
                attachEl.innerHTML = `<a href="<?= url('assets/uploads/chat') ?>/${msg.lampiran}" target="_blank"><i class="bi bi-paperclip"></i> ${msg.lampiran}</a>`;
            }
        }

        bubble.querySelector('.dh-chat-bubble-time').textContent = formatChatTime(msg.created_at);

        html += wrapper.outerHTML;
    });

    container.innerHTML = html;
}

function scrollToBottom() {
    const container = document.getElementById('chatMessages');
    if (container) {
        setTimeout(() => { container.scrollTop = container.scrollHeight; }, 50);
    }
}

// ============ Send Message ============
async function sendMessage() {
    const input = document.getElementById('chatMessageInput');
    const fileInput = document.getElementById('fileAttachment');
    const pesan = input.value.trim();
    if (!pesan && !fileInput.files.length) return;
    if (!currentOrderId || !currentReceiverId) return;

    const btn = document.getElementById('sendBtn');
    btn.disabled = true;

    const formData = new FormData();
    formData.append('order_id', currentOrderId);
    formData.append('receiver_id', currentReceiverId);
    formData.append('pesan', pesan);
    if (fileInput.files.length) {
        formData.append('lampiran', fileInput.files[0]);
    }

    try {
        const res = await fetch('<?= url("api/chat/send") ?>', {
            method: 'POST',
            body: formData
        });
        const data = await res.json();

        if (data.success) {
            input.value = '';
            input.style.height = 'auto';
            fileInput.value = '';
            // Reload messages
            await loadMessages();
            // Refresh conversation list
            loadConversations();
        } else {
            alert(data.error || 'Gagal mengirim pesan');
        }
    } catch (err) {
        console.error('Gagal kirim pesan:', err);
        alert('Gagal mengirim pesan. Silakan coba lagi.');
    }

    btn.disabled = false;
    input.focus();
}

function handleChatKey(event) {
    if (event.key === 'Enter' && !event.shiftKey) {
        event.preventDefault();
        sendMessage();
    }
}

function handleFileSelect(input) {
    if (input.files.length) {
        document.getElementById('sendBtn').disabled = false;
    }
}

// ============ Polling ============
function startPolling() {
    stopPolling();
    chatPollInterval = setInterval(async () => {
        if (!currentOrderId) { stopPolling(); return; }
        try {
            const res = await fetch(`<?= url("api/chat/messages") ?>/${currentOrderId}`);
            const messages = await res.json();
            if (messages.length > 0 && messages[messages.length - 1].id !== lastMessageId) {
                renderMessages(messages);
                lastMessageId = messages[messages.length - 1].id;
                scrollToBottom();
                loadConversations(); // refresh sidebar
            }
        } catch (err) { /* silent */ }
    }, 5000);
}

function stopPolling() {
    if (chatPollInterval) {
        clearInterval(chatPollInterval);
        chatPollInterval = null;
    }
}

// ============ New Chat Modal ============
async function loadAvailableOrders() {
    try {
        const res = await fetch('<?= url("api/chat/orders-for-chat") ?>');
        const orders = await res.json();
        const container = document.getElementById('availableOrdersList');

        if (!orders.length) {
            container.innerHTML = '<div class="text-center py-3 text-muted small">Tidak ada order yang tersedia</div>';
        } else {
            container.innerHTML = orders.map(o => {
                let partnerName = o.customer_nama || o.designer_nama || 'Partner';
                let partnerId = o.customer_id || o.designer_id || 0;
                return `
                <a href="javascript:void(0)" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                   onclick="startNewChat(${o.id}, ${partnerId}, '${escapeHtml(partnerName)}', '${escapeHtml(o.kode_order)}', '${o.status}')">
                    <div>
                        <div class="fw-semibold">${escapeHtml(partnerName)}</div>
                        <small class="text-muted">${escapeHtml(o.kode_order)}</small>
                    </div>
                    <span class="badge bg-${getStatusBadge(o.status)}">${o.status.replace(/_/g, ' ')}</span>
                </a>`;
            }).join('');
        }

        new bootstrap.Modal(document.getElementById('newChatModal')).show();
    } catch (err) {
        console.error('Gagal load order:', err);
    }
}

function startNewChat(orderId, receiverId, partnerName, kodeOrder, status) {
    currentOrderId = orderId;
    currentReceiverId = receiverId;
    currentOrderInfo = { kode: kodeOrder, status: status, partnerName: partnerName };

    // Tutup modal
    bootstrap.Modal.getInstance(document.getElementById('newChatModal')).hide();

    // Buka chat
    const main = document.getElementById('chatMain');
    main.innerHTML = `
        <div class="dh-chat-header">
            <span class="dh-chat-header-back" onclick="closeChat()"><i class="bi bi-arrow-left"></i></span>
            <div class="dh-chat-header-info">
                <div class="dh-chat-header-name">${partnerName}</div>
                <div class="dh-chat-header-desc">${kodeOrder} · <span class="badge bg-${getStatusBadge(status)}">${status.replace(/_/g, ' ')}</span></div>
            </div>
        </div>
        <div class="dh-chat-messages" id="chatMessages">
            <div class="text-center py-4 text-muted small">Belum ada pesan. Kirim pesan pertama!</div>
        </div>
        <div class="dh-chat-input-area">
            <label class="btn btn-sm btn-outline-secondary mb-0" for="fileAttachment" style="border-radius:50%;width:36px;height:36px;display:flex;align-items:center;justify-content:center;padding:0;">
                <i class="bi bi-paperclip"></i>
            </label>
            <input type="file" id="fileAttachment" style="display:none;" onchange="handleFileSelect(this)">
            <textarea id="chatMessageInput" rows="1" placeholder="Ketik pesan..." onkeydown="handleChatKey(event)"></textarea>
            <button class="dh-chat-send-btn" onclick="sendMessage()" id="sendBtn" disabled><i class="bi bi-send-fill"></i></button>
        </div>
    `;

    if (window.innerWidth <= 768) {
        document.querySelector('.dh-chat-sidebar').classList.add('hidden');
        main.classList.add('open');
    }

    const textarea = document.getElementById('chatMessageInput');
    textarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 100) + 'px';
        document.getElementById('sendBtn').disabled = !this.value.trim();
    });

    updateDetailPanel();
    startPolling();
    loadConversations();
}

// ============ Helpers ============
function formatChatTime(datetime) {
    if (!datetime) return '';
    const d = new Date(datetime);
    const now = new Date();
    const isToday = d.toDateString() === now.toDateString();
    if (isToday) {
        return d.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
    }
    const yesterday = new Date(now);
    yesterday.setDate(yesterday.getDate() - 1);
    if (d.toDateString() === yesterday.toDateString()) return 'Kemarin';
    return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
}

function formatDateDivider(datetime) {
    if (!datetime) return '';
    const d = new Date(datetime);
    const now = new Date();
    const isToday = d.toDateString() === now.toDateString();
    if (isToday) return 'Hari ini';
    const yesterday = new Date(now);
    yesterday.setDate(yesterday.getDate() - 1);
    if (d.toDateString() === yesterday.toDateString()) return 'Kemarin';
    return d.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
}

function escapeHtml(str) {
    const div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML;
}

async function updateUnreadBadge() {
    try {
        const res = await fetch('<?= url("api/chat/unread-count") ?>');
        const data = await res.json();
        // Update sidebar badge jika ada
    } catch (err) {}
}

// ============ Init ============
document.addEventListener('DOMContentLoaded', function() {
    loadConversations();
    // Check if order param is present to auto-open
    const urlParams = new URLSearchParams(window.location.search);
    const orderId = urlParams.get('order');
    if (orderId) {
        // Tunggu conversation list loaded, lalu auto open
        setTimeout(() => {
            const item = document.querySelector(`.dh-chat-conversation-item[data-order-id="${orderId}"]`);
            if (item) openChat(item);
        }, 800);
    }
});
</script>