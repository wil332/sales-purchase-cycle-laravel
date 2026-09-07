@extends('admin.layouts.admin')

@section('title', 'ARIA – Asisten Internal ERP')

@section('content')

<style>
    #chat-box {
        height: 520px;
        overflow-y: auto;
        border: 1px solid #dde3ec;
        padding: 20px;
        background: #f5f7fa;
        border-radius: 6px;
    }
    .bubble-user {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 14px;
    }
    .bubble-user .bubble-inner {
        background: #1a73e8;
        color: #fff;
        border-radius: 18px 18px 4px 18px;
        padding: 10px 16px;
        max-width: 75%;
        font-size: 14px;
        line-height: 1.5;
        word-break: break-word;
    }
    .bubble-ai {
        display: flex;
        align-items: flex-start;
        margin-bottom: 14px;
        gap: 10px;
    }
    .bubble-ai .avatar {
        width: 36px;
        height: 36px;
        background: #0f9d58;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 12px;
        font-weight: 700;
        flex-shrink: 0;
    }
    .bubble-ai .bubble-inner {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 4px 18px 18px 18px;
        padding: 10px 16px;
        max-width: 75%;
        font-size: 14px;
        line-height: 1.6;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        word-break: break-word;
    }
    .welcome-box {
        text-align: center;
        padding: 30px 20px;
        color: #475569;
    }
    .welcome-box .aria-icon {
        width: 64px;
        height: 64px;
        background: linear-gradient(135deg, #0f9d58, #1a73e8);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
        font-size: 26px;
        color: white;
    }
    .welcome-box h4 { font-weight: 700; color: #1e293b; margin-bottom: 6px; font-size: 20px; }
    .welcome-box p { font-size: 14px; color: #64748b; margin-bottom: 8px; }
    .quick-chips {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        justify-content: center;
        margin-top: 14px;
    }
    .quick-chip {
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        color: #1d4ed8;
        border-radius: 20px;
        padding: 6px 14px;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-block;
    }
    .quick-chip:hover {
        background: #1d4ed8;
        color: #fff;
        border-color: #1d4ed8;
    }
    .dot {
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #94a3b8;
        animation: bounce 1.2s infinite;
        margin: 0 2px;
    }
    .dot:nth-child(2) { animation-delay: 0.2s; }
    .dot:nth-child(3) { animation-delay: 0.4s; }
    @keyframes bounce {
        0%,60%,100%{ transform:translateY(0); }
        30%{ transform:translateY(-6px); }
    }
    .chat-input-wrap {
        display: flex;
        gap: 10px;
        align-items: center;
        margin-top: 14px;
    }
    .chat-input-wrap input {
        flex: 1;
        border-radius: 24px;
        padding: 10px 18px;
        border: 1px solid #cbd5e1;
        font-size: 14px;
    }
    .chat-input-wrap input:focus {
        outline: none;
        border-color: #1a73e8;
        box-shadow: 0 0 0 3px rgba(26,115,232,0.15);
    }
    .chat-input-wrap button {
        border-radius: 24px;
        padding: 10px 20px;
        font-size: 14px;
        white-space: nowrap;
    }
</style>

<div class="row">
    <div class="col-md-10 col-md-offset-1">

        <div class="panel panel-default" style="border:none;box-shadow:0 2px 12px rgba(0,0,0,0.08);">

            <div class="panel-heading" style="background:linear-gradient(135deg,#0f9d58,#1a73e8);border-radius:6px 6px 0 0;padding:14px 20px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px;">
                <div>
                    <h4 class="panel-title" style="color:#fff;font-weight:700;margin:0;">
                        <i class="fa fa-comments"></i>
                        ARIA — Asisten Referensi Internal Aplikasi ERP
                    </h4>
                    <small style="font-weight:400;font-size:12px;display:block;margin-top:2px;color:rgba(255,255,255,0.85);">
                        Panduan &amp; Onboarding Staf untuk Sistem Purchase &amp; Sales
                    </small>
                </div>
                <div>
                    <span class="label" style="background:rgba(255,255,255,0.22);color:#fff;border:1px solid rgba(255,255,255,0.35);font-size:11px;padding:5px 12px;border-radius:12px;display:inline-flex;align-items:center;gap:6px;">
                        <i class="fa fa-bolt"></i> <strong>{{ strtoupper($provider ?? 'OPENROUTER') }} API</strong> ({{ $model ?? 'meta-llama/llama-3.1-8b-instruct:free' }})
                    </span>
                </div>
            </div>

            <div class="panel-body">

                <div id="chat-box">

                    @forelse($histories as $chat)

                        <div class="bubble-user">
                            <div class="bubble-inner">{{ $chat->question }}</div>
                        </div>

                        <div class="bubble-ai">
                            <div class="avatar">AI</div>
                            <div class="bubble-inner">{!! nl2br(e($chat->answer)) !!}</div>
                        </div>

                    @empty

                        <div class="welcome-box">
                            <div class="aria-icon"><i class="fa fa-robot"></i></div>
                            <h4>Halo, Selamat Datang! 👋</h4>
                            <p>
                                Saya <b>ARIA</b>, Asisten Internal yang membantu Anda memahami<br>
                                cara menggunakan aplikasi ERP Purchase &amp; Sales ini.
                            </p>
                            <p style="font-size:13px;color:#94a3b8;">Klik salah satu topik di bawah, atau ketik pertanyaan Anda sendiri:</p>
                            <div class="quick-chips">
                                <span class="quick-chip" onclick="askQuick(this)">📋 Bagaimana alur pembelian dari awal?</span>
                                <span class="quick-chip" onclick="askQuick(this)">🚚 Bagaimana cara membuat Surat Jalan?</span>
                                <span class="quick-chip" onclick="askQuick(this)">🤔 Apa bedanya Pembayaran Pelanggan dan Xendit?</span>
                                <span class="quick-chip" onclick="askQuick(this)">🏭 Kenapa pilihan barang di PO terbatas?</span>
                                <span class="quick-chip" onclick="askQuick(this)">📦 Apa itu fitur auto-fill di form transaksi?</span>
                                <span class="quick-chip" onclick="askQuick(this)">💡 Saya staf gudang baru, harus mulai dari mana?</span>
                                <span class="quick-chip" onclick="askQuick(this)">📝 Apa arti status 0, 1, 2, 3 pada dokumen?</span>
                            </div>
                        </div>

                    @endforelse

                </div>

                <div class="chat-input-wrap">
                    <input
                        type="text"
                        id="message"
                        placeholder="Tanyakan apa saja tentang cara menggunakan aplikasi ini..."
                        autocomplete="off"
                    >
                    <button id="send" class="btn btn-primary">
                        <i class="fa fa-paper-plane"></i> Kirim
                    </button>
                </div>

            </div>

        </div>

    </div>
</div>

@endsection


@section('scripts')
@parent
<script>

function askQuick(el) {
    var text = el.textContent.trim().replace(/^[\u{1F300}-\u{1FFFF}\s]+/u, '').trim();
    $('#message').val(text);
    sendMessage();
}

$('#send').click(function(){
    sendMessage();
});

$('#message').keypress(function(e){
    if(e.which == 13){
        sendMessage();
    }
});

function sendMessage(){
    var message = $("#message").val().trim();
    if(message === "") return;

    // Append user bubble
    var escapedMsg = $('<div>').text(message).html();
    $("#chat-box").append(
        '<div class="bubble-user">' +
        '<div class="bubble-inner">' + escapedMsg + '</div>' +
        '</div>'
    );

    $("#message").val("");
    scrollChat();

    // Show typing indicator
    $("#chat-box").append(
        '<div class="bubble-ai typing-indicator" id="aria-typing">' +
        '<div class="avatar">AI</div>' +
        '<div class="bubble-inner"><span class="dot"></span><span class="dot"></span><span class="dot"></span></div>' +
        '</div>'
    );
    scrollChat();

    $.ajax({
        url: "{{ route('admin.chatbot.chat') }}",
        method: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            message: message
        },
        success: function(res){
            $("#aria-typing").remove();
            var safeReply = $('<div>').text(res.reply).html().replace(/\n/g, "<br>");
            $("#chat-box").append(
                '<div class="bubble-ai">' +
                '<div class="avatar">AI</div>' +
                '<div class="bubble-inner">' + safeReply + '</div>' +
                '</div>'
            );
            scrollChat();
        },
        error: function(){
            $("#aria-typing").remove();
            $("#chat-box").append(
                '<div class="bubble-ai">' +
                '<div class="avatar" style="background:#e53e3e;">!</div>' +
                '<div class="bubble-inner" style="color:#c53030;">Gagal menghubungi ARIA. Periksa koneksi atau coba beberapa saat lagi.</div>' +
                '</div>'
            );
            scrollChat();
        }
    });
}

function scrollChat(){
    var box = document.getElementById('chat-box');
    box.scrollTop = box.scrollHeight;
}

</script>
@endsection