<style>
    * {
        box-sizing: border-box;
    }

    body {
        font-family: "Segoe UI", Arial, sans-serif;
    }

    /* Floating Chat Button */
    #chatbot-btn {
        position: fixed;
        bottom: 20px;
        right: 20px;
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: linear-gradient(135deg, #1d4ed8, #2563eb, #3b82f6);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        cursor: pointer;
        box-shadow: 0 10px 25px rgba(37, 99, 235, 0.35);
        z-index: 99999;
        transition: transform 0.2s ease;
    }

    #chatbot-btn:hover {
        transform: scale(1.06);
    }

    /* Chatbox */
    #chatbox {
        display: none;
        position: fixed;
        bottom: 95px;
        right: 20px;
        width: 390px;
        height: 620px;
        background: #ffffff;
        border-radius: 22px;
        box-shadow: 0 20px 45px rgba(0,0,0,0.18);
        overflow: hidden;
        z-index: 99999;
        border: 1px solid #e5e7eb;
    }

    /* Header */
    #chat-header {
        background: linear-gradient(135deg, #1d4ed8, #2563eb);
        color: #fff;
        padding: 16px 18px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .chat-header-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .bot-avatar {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: rgba(255,255,255,0.18);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .chat-title {
        font-size: 16px;
        font-weight: 700;
    }

    .chat-subtitle {
        font-size: 12px;
        opacity: 0.9;
        margin-top: 2px;
    }

    .chat-actions {
        display: flex;
        gap: 10px;
        font-size: 18px;
        cursor: pointer;
    }

    .chat-actions span {
        opacity: 0.95;
    }

    /* Category strip */
    #category-strip {
        display: flex;
        gap: 8px;
        padding: 10px 12px;
        background: #f8fafc;
        border-bottom: 1px solid #eef2f7;
        overflow-x: auto;
        scrollbar-width: none;
    }

    #category-strip::-webkit-scrollbar {
        display: none;
    }

    .cat-btn {
        white-space: nowrap;
        padding: 8px 14px;
        border-radius: 999px;
        border: 1px solid #dbeafe;
        background: #eff6ff;
        color: #1d4ed8;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: 0.2s ease;
    }

    .cat-btn:hover {
        background: #dbeafe;
    }

    /* Quick buttons */
    #quick-buttons {
        padding: 10px 12px;
        background: #ffffff;
        border-bottom: 1px solid #eef2f7;
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    #quick-buttons button {
        padding: 8px 12px;
        border: none;
        border-radius: 999px;
        background: #f3f4f6;
        color: #111827;
        cursor: pointer;
        font-size: 12px;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    #quick-buttons button:hover {
        background: #e5e7eb;
    }

    /* Chat body */
    #chat-body {
        height: 385px;
        overflow-y: auto;
        padding: 14px;
        background: linear-gradient(to bottom, #f8fbff, #ffffff);
        scroll-behavior: smooth;
    }

    .msg {
        display: flex;
        margin: 12px 0;
    }

    .msg.user {
        justify-content: flex-end;
    }

    .msg.bot {
        justify-content: flex-start;
    }

    .msg-bubble {
        max-width: 82%;
        padding: 12px 14px;
        border-radius: 18px;
        line-height: 1.5;
        font-size: 14px;
        word-wrap: break-word;
        box-shadow: 0 4px 10px rgba(0,0,0,0.04);
    }

    .msg.user .msg-bubble {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: #fff;
        border-bottom-right-radius: 6px;
    }

    .msg.bot .msg-bubble {
        background: #ffffff;
        color: #111827;
        border: 1px solid #e5e7eb;
        border-bottom-left-radius: 6px;
    }

    .msg-label {
        font-size: 11px;
        margin-bottom: 4px;
        opacity: 0.75;
        font-weight: 600;
    }

    /* Typing animation */
    .typing-wrap {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 3px 2px;
    }

    .typing-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #6b7280;
        animation: bounce 1.2s infinite ease-in-out;
    }

    .typing-dot:nth-child(2) {
        animation-delay: 0.2s;
    }

    .typing-dot:nth-child(3) {
        animation-delay: 0.4s;
    }

    @keyframes bounce {
        0%, 80%, 100% {
            transform: scale(0.7);
            opacity: 0.5;
        }
        40% {
            transform: scale(1);
            opacity: 1;
        }
    }

    /* Footer */
    #chat-footer {
        border-top: 1px solid #e5e7eb;
        background: #fff;
        padding: 10px;
        display: flex;
        gap: 8px;
        align-items: center;
    }

    #userInput {
        flex: 1;
        border: 1px solid #d1d5db;
        border-radius: 999px;
        padding: 12px 15px;
        outline: none;
        font-size: 14px;
    }

    #userInput:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
    }

    #sendBtn {
        border: none;
        width: 46px;
        height: 46px;
        border-radius: 50%;
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: white;
        font-size: 18px;
        cursor: pointer;
    }

    #sendBtn:hover {
        opacity: 0.95;
    }

    .small-note {
        text-align: center;
        font-size: 11px;
        color: #6b7280;
        padding-bottom: 6px;
    }

    @media(max-width: 480px) {
        #chatbox {
            width: calc(100% - 20px);
            right: 10px;
            left: 10px;
            bottom: 85px;
            height: 88vh;
        }

        #chat-body {
            height: calc(88vh - 240px);
        }
    }
</style>

<div id="chatbot-btn" onclick="toggleChat()">💬</div>

<div id="chatbox">
    <div id="chat-header">
        <div class="chat-header-left">
            <div class="bot-avatar">🤖</div>
            <div>
                <div class="chat-title">Admission Assistant</div>
                <div class="chat-subtitle">JEE / NRI / OCI Help Desk</div>
            </div>
        </div>
        <div class="chat-actions">
            <span onclick="switchLang()" title="Switch Language">🌐</span>
            <span onclick="toggleChat()" title="Close">✖</span>
        </div>
    </div>

    <div id="category-strip">
        <div class="cat-btn" onclick="setCategory('general')">General</div>
        <div class="cat-btn" onclick="setCategory('jee')">JEE</div>
        <div class="cat-btn" onclick="setCategory('nri')">NRI</div>
        <div class="cat-btn" onclick="setCategory('oci')">OCI</div>
    </div>

    <div id="quick-buttons">
        <button onclick="quickReply('eligibility')">🎯 Eligibility</button>
        <button onclick="quickReply('documents')">📄 Documents</button>
        <button onclick="quickReply('upload')">📎 Upload Help</button>
        <button onclick="quickReply('status')">📌 Check Status</button>
        <button onclick="quickReply('fee')">💰 Fee</button>
        <button onclick="quickReply('contact')">☎ Contact</button>
    </div>

    <div id="chat-body"></div>

    <div id="chat-footer">
        <input type="text" id="userInput" placeholder="Type your question..."
               onkeypress="handleKey(event)">
        <button id="sendBtn" onclick="sendMessage()">➤</button>
    </div>

    <div class="small-note">Ask about JEE, NRI, OCI, documents, fees, status, uploads.</div>
</div>

<script>
let lang = "en";
let currentCategory = "general";
let typingTimer = null;

/* Open / Close */
function toggleChat() {
    const box = document.getElementById("chatbox");
    if (box.style.display === "block") {
        box.style.display = "none";
    } else {
        box.style.display = "block";
        if (document.getElementById("chat-body").innerHTML.trim() === "") {
            welcomeMessage();
        }
    }
}

/* Welcome */
function welcomeMessage() {
    let text = (lang === "en")
        ? "Hello 👋 I am your Admission Assistant. Select a category like <b>JEE</b>, <b>NRI</b>, or <b>OCI</b>, or ask about eligibility, documents, fees, upload help, or application status."
        : "హలో 👋 నేను మీ Admission Assistant ని. <b>JEE</b>, <b>NRI</b>, <b>OCI</b> కేటగిరీలను ఎంచుకోండి లేదా అర్హత, పత్రాలు, ఫీజు, అప్‌లోడ్ సహాయం, లేదా అప్లికేషన్ స్థితి గురించి అడగండి.";
    addMessage("bot", text);
}

/* Set category */
function setCategory(cat) {
    currentCategory = cat;

    let text = "";
    if (lang === "en") {
        if (cat === "jee") text = "You selected <b>JEE</b>. Ask me about JEE eligibility, rank card, documents, or status.";
        else if (cat === "nri") text = "You selected <b>NRI</b>. Ask me about passport, sponsor documents, certificates, or status.";
        else if (cat === "oci") text = "You selected <b>OCI</b>. Ask me about OCI card, passport, academic documents, or status.";
        else text = "You selected <b>General</b>. Ask me anything about the admission process.";
    } else {
        if (cat === "jee") text = "మీరు <b>JEE</b> ఎంచుకున్నారు. JEE అర్హత, ర్యాంక్ కార్డ్, పత్రాలు లేదా స్టేటస్ గురించి అడగండి.";
        else if (cat === "nri") text = "మీరు <b>NRI</b> ఎంచుకున్నారు. పాస్‌పోర్ట్, స్పాన్సర్ పత్రాలు, సర్టిఫికేట్లు లేదా స్టేటస్ గురించి అడగండి.";
        else if (cat === "oci") text = "మీరు <b>OCI</b> ఎంచుకున్నారు. OCI కార్డ్, పాస్‌పోర్ట్, అకాడమిక్ పత్రాలు లేదా స్టేటస్ గురించి అడగండి.";
        else text = "మీరు <b>General</b> ఎంచుకున్నారు. అడ్మిషన్ ప్రక్రియ గురించి ఏదైనా అడగండి.";
    }

    addMessage("bot", text);
}

/* Enter key */
function handleKey(e) {
    if (e.key === "Enter") {
        sendMessage();
    }
}

/* Send message */
function sendMessage() {
    const input = document.getElementById("userInput");
    const msg = input.value.trim();
    if (msg === "") return;

    addMessage("user", escapeHtml(msg));
    input.value = "";

    botReply(msg);
}

/* Quick buttons */
function quickReply(type) {
    if (type === "status") {
        let reference_no = prompt(lang === "en" ? "Enter Reference Number:" : "రిఫరెన్స్ నంబర్ ఇవ్వండి:");
        if (reference_no) {
            addMessage("user", escapeHtml(reference_no));
            checkStatus(reference_no);
        }
        return;
    }

    let labelMap = {
        eligibility: "Eligibility",
        documents: "Documents",
        upload: "Upload Help",
        fee: "Fee Details",
        contact: "Contact"
    };

    addMessage("user", labelMap[type] || type);
    botReply(type);
}

/* Add message */
function addMessage(sender, text) {
    const chat = document.getElementById("chat-body");

    const msgDiv = document.createElement("div");
    msgDiv.className = "msg " + sender;

    const bubbleWrap = document.createElement("div");

    const label = document.createElement("div");
    label.className = "msg-label";
    label.innerHTML = sender === "user"
        ? (lang === "en" ? "You" : "మీరు")
        : "Assistant";

    const bubble = document.createElement("div");
    bubble.className = "msg-bubble";
    bubble.innerHTML = text;

    bubbleWrap.appendChild(label);
    bubbleWrap.appendChild(bubble);
    msgDiv.appendChild(bubbleWrap);

    chat.appendChild(msgDiv);
    chat.scrollTop = chat.scrollHeight;
}

/* Typing animation */
function showTyping() {
    const chat = document.getElementById("chat-body");

    const typingDiv = document.createElement("div");
    typingDiv.className = "msg bot";
    typingDiv.id = "typing-indicator";
    typingDiv.innerHTML = `
        <div>
            <div class="msg-label">Assistant</div>
            <div class="msg-bubble">
                <div class="typing-wrap">
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                </div>
            </div>
        </div>
    `;
    chat.appendChild(typingDiv);
    chat.scrollTop = chat.scrollHeight;
}

function removeTyping() {
    const typing = document.getElementById("typing-indicator");
    if (typing) typing.remove();
}

/* Language switch */
function switchLang() {
    lang = (lang === "en") ? "te" : "en";
    addMessage("bot", lang === "en" ? "Language changed to English." : "భాష తెలుగుకు మార్చబడింది.");
}

/* Safe escape */
function escapeHtml(text) {
    const div = document.createElement("div");
    div.textContent = text;
    return div.innerHTML;
}

/* Status check */
function checkStatus(reference_no) {
    showTyping();

    fetch("check_chatbot_status.php?reference_no=" + encodeURIComponent(reference_no))
        .then(response => response.text())
        .then(data => {
            removeTyping();
            addMessage("bot", data);
        })
        .catch(error => {
            removeTyping();
            addMessage("bot", lang === "en"
                ? "Unable to check status right now. Please try again later."
                : "ఇప్పుడు స్థితిని చెక్ చేయలేకపోతున్నాం. తర్వాత మళ్లీ ప్రయత్నించండి.");
        });
}

/* Main reply logic */
function botReply(rawMsg) {
    const msg = rawMsg.toLowerCase();

    showTyping();

    clearTimeout(typingTimer);
    typingTimer = setTimeout(() => {
        removeTyping();

        let reply = getSmartReply(msg);
        addMessage("bot", reply);

    }, 900);
}

/* Smart replies */
function getSmartReply(msg) {
    /* Greeting */
    if (msg.includes("hi") || msg.includes("hello") || msg.includes("hey")) {
        return (lang === "en")
            ? "Hello 👋 How can I help you today? You can ask about <b>JEE</b>, <b>NRI</b>, <b>OCI</b>, documents, upload help, fees, or application status."
            : "హలో 👋 నేను ఎలా సహాయం చేయగలను? <b>JEE</b>, <b>NRI</b>, <b>OCI</b>, పత్రాలు, అప్‌లోడ్ సహాయం, ఫీజులు లేదా అప్లికేషన్ స్థితి గురించి అడగండి.";
    }

    /* JEE replies */
    if (currentCategory === "jee" || msg.includes("jee") || msg.includes("rank") || msg.includes("eamcet")) {
        if (msg.includes("eligibility")) {
            return (lang === "en")
                ? "<b>JEE Eligibility:</b><br>Students generally need valid academic qualification and the required entrance exam details. Keep your rank information and academic certificates ready."
                : "<b>JEE అర్హత:</b><br>విద్యార్థులకు అవసరమైన విద్యార్హత మరియు ఎంట్రన్స్ వివరాలు అవసరం. మీ ర్యాంక్ వివరాలు మరియు అకాడమిక్ సర్టిఫికేట్లు సిద్ధంగా ఉంచండి.";
        }

        if (msg.includes("documents") || msg.includes("certificate")) {
            return (lang === "en")
                ? "<b>JEE Documents:</b><br>1. Photo<br>2. 10th Memo<br>3. Inter Memo<br>4. JEE / Rank Card<br>5. Aadhar / ID Proof<br>6. Student Signature<br>7. Parent Signature"
                : "<b>JEE పత్రాలు:</b><br>1. ఫోటో<br>2. 10వ మెమో<br>3. ఇంటర్ మెమో<br>4. JEE / ర్యాంక్ కార్డ్<br>5. ఆధార్ / ఐడి ప్రూఫ్<br>6. విద్యార్థి సంతకం<br>7. తల్లిదండ్రుల సంతకం";
        }

        if (msg.includes("rank card") || msg.includes("upload")) {
            return (lang === "en")
                ? "For <b>JEE rank card upload</b>, please upload the file in PDF format, make sure it is clear, and keep it within the allowed size limit."
                : "<b>JEE ర్యాంక్ కార్డ్ అప్‌లోడ్</b> కోసం PDF ఫార్మాట్‌లో ఫైల్ అప్‌లోడ్ చేయండి, అది స్పష్టంగా ఉండాలి మరియు అనుమతించిన పరిమితి సైజులో ఉండాలి.";
        }

        return (lang === "en")
            ? "You are asking about <b>JEE</b>. I can help with JEE eligibility, rank card upload, required documents, fees, and status."
            : "మీరు <b>JEE</b> గురించి అడుగుతున్నారు. JEE అర్హత, ర్యాంక్ కార్డ్ అప్‌లోడ్, అవసరమైన పత్రాలు, ఫీజులు, మరియు స్టేటస్ గురించి సహాయం చేస్తాను.";
    }

    /* NRI replies */
    if (currentCategory === "nri" || msg.includes("nri") || msg.includes("sponsor") || msg.includes("passport")) {
        if (msg.includes("eligibility")) {
            return (lang === "en")
                ? "<b>NRI Eligibility:</b><br>NRI applicants usually need academic qualification, passport-related documents, and supporting records based on the admission requirements."
                : "<b>NRI అర్హత:</b><br>NRI విద్యార్థులకు సాధారణంగా విద్యార్హత, పాస్‌పోర్ట్ సంబంధిత పత్రాలు, మరియు అవసరమైన సహాయక రికార్డులు అవసరం.";
        }

        if (msg.includes("documents") || msg.includes("certificate")) {
            return (lang === "en")
                ? "<b>NRI Documents:</b><br>1. Student Passport<br>2. Sponsor Passport / NRI Proof<br>3. 10th Memo<br>4. Inter Memo<br>5. Photo<br>6. Student Signature<br>7. Parent Signature<br>8. Other supporting documents if required"
                : "<b>NRI పత్రాలు:</b><br>1. విద్యార్థి పాస్‌పోర్ట్<br>2. స్పాన్సర్ పాస్‌పోర్ట్ / NRI ప్రూఫ్<br>3. 10వ మెమో<br>4. ఇంటర్ మెమో<br>5. ఫోటో<br>6. విద్యార్థి సంతకం<br>7. తల్లిదండ్రుల సంతకం<br>8. అవసరమైతే ఇతర పత్రాలు";
        }

        if (msg.includes("passport") || msg.includes("upload")) {
            return (lang === "en")
                ? "For <b>NRI uploads</b>, keep passport and sponsor documents clear and upload them in the required format such as PDF or image, based on your form."
                : "<b>NRI అప్‌లోడ్</b> కోసం పాస్‌పోర్ట్ మరియు స్పాన్సర్ పత్రాలు స్పష్టంగా ఉండాలి మరియు మీ ఫారమ్‌కు అనుగుణంగా PDF లేదా ఇమేజ్ ఫార్మాట్‌లో అప్‌లోడ్ చేయాలి.";
        }

        return (lang === "en")
            ? "You are asking about <b>NRI admissions</b>. I can help with passport documents, sponsor proof, uploads, eligibility, and status."
            : "మీరు <b>NRI అడ్మిషన్స్</b> గురించి అడుగుతున్నారు. పాస్‌పోర్ట్ పత్రాలు, స్పాన్సర్ ప్రూఫ్, అప్‌లోడ్స్, అర్హత, మరియు స్టేటస్‌లో సహాయం చేస్తాను.";
    }

    /* OCI replies */
    if (currentCategory === "oci" || msg.includes("oci") || msg.includes("oci card")) {
        if (msg.includes("eligibility")) {
            return (lang === "en")
                ? "<b>OCI Eligibility:</b><br>OCI applicants generally need valid academic qualification along with OCI and passport-related identification documents."
                : "<b>OCI అర్హత:</b><br>OCI విద్యార్థులకు సాధారణంగా విద్యార్హతతో పాటు OCI మరియు పాస్‌పోర్ట్‌కు సంబంధించిన గుర్తింపు పత్రాలు అవసరం.";
        }

        if (msg.includes("documents") || msg.includes("certificate")) {
            return (lang === "en")
                ? "<b>OCI Documents:</b><br>1. OCI Card<br>2. Passport<br>3. 10th Memo<br>4. Inter Memo<br>5. Photo<br>6. Student Signature<br>7. Parent Signature<br>8. Identity proof if needed"
                : "<b>OCI పత్రాలు:</b><br>1. OCI కార్డ్<br>2. పాస్‌పోర్ట్<br>3. 10వ మెమో<br>4. ఇంటర్ మెమో<br>5. ఫోటో<br>6. విద్యార్థి సంతకం<br>7. తల్లిదండ్రుల సంతకం<br>8. అవసరమైతే ఐడి ప్రూఫ్";
        }

        if (msg.includes("oci card") || msg.includes("upload")) {
            return (lang === "en")
                ? "For <b>OCI document upload</b>, make sure the OCI card copy is clear and uploaded in the required format."
                : "<b>OCI పత్రాల అప్‌లోడ్</b> కోసం OCI కార్డ్ కాపీ స్పష్టంగా ఉండాలి మరియు అవసరమైన ఫార్మాట్‌లో అప్‌లోడ్ చేయాలి.";
        }

        return (lang === "en")
            ? "You are asking about <b>OCI admissions</b>. I can help with OCI card documents, passport, uploads, eligibility, and status."
            : "మీరు <b>OCI అడ్మిషన్స్</b> గురించి అడుగుతున్నారు. OCI కార్డ్ పత్రాలు, పాస్‌పోర్ట్, అప్‌లోడ్స్, అర్హత, మరియు స్టేటస్‌లో సహాయం చేస్తాను.";
    }

    /* General eligibility */
    if (msg.includes("eligibility") || msg.includes("eligible") || msg.includes("criteria")) {
        return (lang === "en")
            ? "<b>General Eligibility:</b><br>Students usually need valid academic qualifications, required entrance details where applicable, and all mandatory supporting documents."
            : "<b>సాధారణ అర్హత:</b><br>విద్యార్థులకు సాధారణంగా విద్యార్హత, అవసరమైతే ఎంట్రన్స్ వివరాలు, మరియు తప్పనిసరి పత్రాలు అవసరం.";
    }

    /* General documents */
    if (msg.includes("documents") || msg.includes("document") || msg.includes("certificates")) {
        return (lang === "en")
            ? "<b>Common Documents:</b><br>Photo, 10th Memo, Inter Memo, ID Proof, relevant rank/entrance card, and signatures. Category-specific documents depend on JEE, NRI, or OCI."
            : "<b>సాధారణ పత్రాలు:</b><br>ఫోటో, 10వ మెమో, ఇంటర్ మెమో, ఐడి ప్రూఫ్, సంబంధిత ర్యాంక్/ఎంట్రన్స్ కార్డ్, మరియు సంతకాలు. JEE, NRI, లేదా OCI ఆధారంగా మరిన్ని పత్రాలు మారవచ్చు.";
    }

    /* Upload help */
    if (msg.includes("upload") || msg.includes("file") || msg.includes("pdf") || msg.includes("image") || msg.includes("photo")) {
        return (lang === "en")
            ? "<b>Upload Help:</b><br>Use clear JPG/PNG images for photos and signatures. Use PDF for certificates and rank cards if required. Make sure file size is within the allowed limit."
            : "<b>అప్‌లోడ్ సహాయం:</b><br>ఫోటోలు మరియు సంతకాల కోసం స్పష్టమైన JPG/PNG ఇమేజెస్ ఉపయోగించండి. అవసరమైతే సర్టిఫికేట్లు మరియు ర్యాంక్ కార్డులకు PDF ఉపయోగించండి. ఫైల్ సైజు పరిమితిలో ఉండాలి.";
    }

    /* Fee */
    if (msg.includes("fee") || msg.includes("fees") || msg.includes("payment") || msg.includes("pay")) {
        return (lang === "en")
            ? "<b>Fee Details:</b><br>Fees may vary based on admission category. Please check the official fee section or contact the admission office for exact details."
            : "<b>ఫీజు వివరాలు:</b><br>ఫీజులు అడ్మిషన్ కేటగిరీపై ఆధారపడి మారవచ్చు. ఖచ్చితమైన వివరాల కోసం అధికారిక ఫీజు విభాగాన్ని చూడండి లేదా అడ్మిషన్ ఆఫీసును సంప్రదించండి.";
    }

    /* Status */
    if (msg.includes("status") || msg.includes("application status") || msg.includes("check status")) {
        return (lang === "en")
            ? "Please click <b>Check Status</b> and enter your reference number."
            : "<b>Check Status</b> పై క్లిక్ చేసి మీ రిఫరెన్స్ నంబర్ ఇవ్వండి.";
    }

    /* Contact */
    if (msg.includes("contact") || msg.includes("phone") || msg.includes("email") || msg.includes("help")) {
        return (lang === "en")
            ? "<b>Contact Help:</b><br>Please contact the admission office using the phone number or email given on your website contact page."
            : "<b>సంప్రదింపు సహాయం:</b><br>మీ వెబ్‌సైట్ కాంటాక్ట్ పేజీలో ఉన్న ఫోన్ నంబర్ లేదా ఇమెయిల్ ద్వారా అడ్మిషన్ ఆఫీసును సంప్రదించండి.";
    }

    /* Default */
    return (lang === "en")
        ? "I can help with <b>JEE</b>, <b>NRI</b>, <b>OCI</b>, eligibility, documents, file uploads, fees, contact details, and application status. Please ask clearly or choose a category."
        : "<b>JEE</b>, <b>NRI</b>, <b>OCI</b>, అర్హత, పత్రాలు, ఫైల్ అప్‌లోడ్, ఫీజులు, సంప్రదింపు వివరాలు, మరియు అప్లికేషన్ స్థితి గురించి నేను సహాయం చేయగలను. దయచేసి స్పష్టంగా అడగండి లేదా ఒక కేటగిరీని ఎంచుకోండి.";
}
</script>