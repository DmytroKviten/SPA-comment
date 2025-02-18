<!DOCTYPE html>
<html lang="uk">
<head>
  <meta charset="UTF-8">
  <title>Коментарі та Профіль</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <style>
    body {
      font-family: 'Helvetica Neue', Arial, sans-serif;
      background: #f9fafc;
      margin: 0;
      padding: 30px;
      display: flex;
      min-height: 100vh;
    }
    .container {
      display: flex;
      width: 100%;
      max-width: 1200px;
      margin: auto;
      gap: 30px;
    }
    .main-content {
      flex: 3;
      background: #fff;
      padding: 25px;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .sidebar {
      flex: 1;
      background: #fff;
      padding: 25px;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      max-width: 320px;
    }
    h1, h2 {
      text-align: center;
      margin-top: 0;
      margin-bottom: 20px;
    }
    form input, form textarea, form button {
      width: 100%;
      padding: 12px;
      margin-bottom: 15px;
      border: 1px solid #ddd;
      border-radius: 4px;
      box-sizing: border-box;
      font-size: 1em;
    }
    form textarea {
      min-height: 100px;
      resize: vertical;
    }
    form button {
      background: #007BFF;
      color: #fff;
      border: none;
      cursor: pointer;
      transition: background 0.3s;
    }
    form button:hover {
      background: #0056b3;
    }
    .profile-image {
      display: block;
      max-width: 120px;
      border-radius: 50%;
      margin: 0 auto 20px auto;
    }

    #attachment {
      display: none !important;
    }
    .paperclip-label {
      display: inline-block;
      vertical-align: middle;
      cursor: pointer;
      margin-bottom: 15px;
      padding: 8px 12px;
      background-color: #e7e7e7;
      border: 1px solid #ccc;
      border-radius: 4px;
      font-size: 0.95em;
      color: #333;
    }
    .disabled {
      opacity: 0.6;
      pointer-events: none;
    }
    .notification {
      color: red;
      text-align: center;
      margin-bottom: 20px;
      font-weight: bold;
    }
    .reset-button {
      background: #dc3545;
      color: #fff;
      border: none;
      padding: 10px;
      border-radius: 4px;
      width: 100%;
      cursor: pointer;
      margin-top: 10px;
      transition: background 0.3s;
    }
    .reset-button:hover {
      background: #c82333;
    }
    .load-more-button {
      background: #007BFF;
      color: #fff;
      border: none;
      padding: 8px 12px;
      border-radius: 4px;
      cursor: pointer;
      margin: 10px 0;
      transition: background 0.3s;
    }
    .load-more-button:hover {
      background: #0056b3;
    }
    /* Коментарі */
    .comment {
      border: 1px solid #ddd;
      padding: 20px;
      border-radius: 8px;
      margin-bottom: 20px;
      background: #fff;
    }
    .comment-header {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 8px;
    }
    .comment-avatar {
      width: 40px;
      height: 40px;
      border-radius: 50%;
    }
    .comment .text {
      margin-top: 8px;
    }
    .comment .comment-date {
      font-size: 0.9em;
      color: #777;
      margin-left: 10px;
    }
    .comment img.comment-image {
      max-width: 100%;
      margin-top: 10px;
      border-radius: 4px;
      cursor: pointer;
      transition: transform 0.3s;
    }
    .comment img.comment-image:hover {
      transform: scale(1.02);
    }
    .reply-button {
      background: #28a745;
      color: #fff;
      border: none;
      padding: 8px 12px;
      border-radius: 4px;
      cursor: pointer;
      margin-top: 10px;
      transition: background 0.3s;
    }
    .reply-button:hover {
      background: #218838;
    }
    .reply-form {
      border-top: 1px dashed #ccc;
      margin-top: 15px;
      padding-top: 15px;
    }
    .children-container {
      margin-top: 10px;
    }

    /* Lightbox стилі */
    .lightbox {
      position: fixed;
      z-index: 1000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0,0,0,0.8);
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .lightbox-content {
      display: block;
      max-width: 60%;
      max-height: 80%;
      transition: transform 0.3s ease;
      margin: 0;
    }
    .lightbox-content:hover {
      transform: scale(1.1);
    }
    .lightbox-close {
      position: absolute;
      top: 15px;
      right: 35px;
      color: #fff;
      font-size: 40px;
      font-weight: bold;
      cursor: pointer;
    }
    @media only screen and (max-width: 700px) {
      .lightbox-content {
        width: 100%;
      }
    }
    .lightbox-content.expanded {
      max-width: 100vw !important;
      max-height: 100vh !important;
      width: auto;
      height: auto;
    }
    /* кнопка аватара */
    .avatar-button {
      display: block;
      width: 100%;
      padding: 8px 16px;
      font-size: 0.8em;
      font-weight: bold;
      color: white;
      background: linear-gradient(135deg, #ff7eb3, #ff758c);
      border: none;
      border-radius: 8px;
      cursor: pointer;
      transition: all 0.3s ease;
      text-align: center;
      margin-bottom: 20px;
    }
    .avatar-button:hover {
      background: linear-gradient(135deg, #ff528d, #ff6a7d);
      transform: scale(1.05);
    }
    /* Пагінація */
    #paginationControls {
      margin-top: 10px;
      text-align: center;
    }
    .pagination-button {
      margin: 0 2px;
      padding: 6px 10px;
      cursor: pointer;
      border: 1px solid #ccc;
      background: #fff;
      border-radius: 4px;
    }
    .pagination-button:hover {
      background: #e9e9e9;
    }
    .pagination-button.active {
      font-weight: bold;
      background: #007BFF;
      color: #fff;
      border-color: #007BFF;
    }

    /* Кнопки сортування  */
    .sort-container {
      display: flex;
      gap: 10px;
      margin-top: 15px; 
      flex-wrap: wrap;
      align-items: center;
    }
    .sort-container .sort-button {
      padding: 8px 12px;
      border: 1px solid #ccc;
      border-radius: 4px;
      cursor: pointer;
      background: #f2f2f2;
      transition: background 0.3s;
    }
    .sort-container .sort-button:hover {
      background: #e2e2e2;
    }
    .sort-info {
      margin-left: auto;
      font-size: 0.9em;
      color: #555;
    }
  </style>
</head>
<body>
  <div class="container">

    <!-- Основна секція для коментарів -->
    <div class="main-content">
      <h1>Коментарі</h1>
      <div id="commentNotification" class="notification" style="display: none;">
        Будь ласка, заповніть свій профіль праворуч, щоб залишати коментарі.
      </div>

      <!-- Форма додавання коментаря -->
      <form id="commentForm">
        @csrf
        <textarea name="text" placeholder="Ваш коментар" required></textarea>
        <input type="text" name="captcha" placeholder="Введіть капчу (1234)" required>
        <label for="attachment" class="paperclip-label">📎 Прикріпити файл</label>
        <input type="file" id="attachment" name="attachment">
        <button type="submit">Надіслати коментар</button>
      </form>

      <div class="sort-container">
        <span>Сортувати за:</span>
        <button class="sort-button" onclick="changeSort('email')">Email</button>
        <button class="sort-button" onclick="changeSort('created_at')">Час</button>
        <button class="sort-button" onclick="changeSort('text')">Текст</button>
        <span id="currentSortInfo" class="sort-info"></span>
      </div>

      <!-- Список коментарів -->
      <div id="commentsList" style="margin-top: 20px;">
        <!-- тут буде JS -->
      </div>

      <!-- Пагінація -->
      <div id="paginationControls"></div>
    </div>

    <!-- Профіль -->
    <div class="sidebar">
      <h2>Ваш профіль</h2>
      <div id="profileDisplay" style="text-align: center;">
        <img id="profileImage" class="profile-image" src="{{ asset('images/default-profile.png') }}" alt="Profile Image">
      </div>
      <button id="generateAvatarBtn" type="button" class="avatar-button">
        🎨 Згенерувати аватар
      </button>

      <!-- Редагування профіля профілю -->
      <form id="profileForm">
        @csrf
        <input type="hidden" name="avatar_url" id="avatarUrlField">
        <input type="text" name="user_name" placeholder="Ім'я користувача" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="url" name="home_page" placeholder="Домашня сторінка (опціонально)">
        <button type="submit">Зберегти профіль</button>
      </form>

      <!-- скидання -->
      <button id="resetProfile" class="reset-button">Скинути профіль</button>
    </div>
  </div>

  <div id="lightbox" class="lightbox" style="display: none;">
    <span class="lightbox-close" id="lightbox-close">&times;</span>
    <img class="lightbox-content" id="lightbox-img" src="">
  </div>

  <script>
    // --- сортування і пагінація ---
    let currentPage = 1;
    let currentSortField = 'created_at';  
    let currentSortOrder = 'desc';       

    
    // 1. Рендер коментарів (список + діти)
  
    function renderComment(comment, level = 0) {
      let avatarUrl = (comment.avatar_url && comment.avatar_url.trim() !== "")
        ? comment.avatar_url
        : '/images/default-profile.png';

      let imageHtml = '';
      if (comment.attachment_type === 'image' && comment.attachment_path) {
        imageHtml = `
          <br>
          <img 
            class="comment-image" 
            src="/${comment.attachment_path}" 
            alt="Image"
          >
        `;
      }

      let html = `
        <div 
          class="comment" 
          id="comment-${comment.id}"
          style="margin-left: ${level * 30}px;"
        >
          <div class="comment-header">
            <img class="comment-avatar" src="${avatarUrl}" alt="Avatar">
            <strong>${comment.user_name}</strong>
            <span class="comment-date">
              ${new Date(comment.created_at).toLocaleString()}
            </span>
          </div>
          <div class="text">
            ${comment.text}
            ${imageHtml}
          </div>
          <button 
            class="reply-button" 
            onclick="toggleReplyForm(${comment.id})"
          >
            Відповісти
          </button>
          <div 
            class="reply-form" 
            id="reply-form-${comment.id}" 
            style="display:none;"
          >
            <form onsubmit="submitReply(event, ${comment.id})">
              <textarea name="text" placeholder="Ваша відповідь" required></textarea>
              <input type="text" name="captcha" placeholder="Введіть капчу (1234)" required>
              <label for="reply-attachment-${comment.id}" class="paperclip-label">📎 Прикріпити файл</label>
              <input type="file" id="reply-attachment-${comment.id}" name="attachment">
              <button type="submit">Надіслати відповідь</button>
            </form>
          </div>
        </div>
      `;

      // Діти (відповіді)
      if (comment.children && comment.children.length > 0) {
        html += `
          <div 
            class="children-container" 
            id="children-container-${comment.id}" 
            data-visible="2"
          >
        `;
        comment.children.forEach((child, index) => {
          let displayStyle = (index < 2) ? 'display: block;' : 'display: none;';
          html += `
            <div 
              class="child-comment" 
              data-index="${index}" 
              style="${displayStyle}"
            >
              ${renderComment(child, level + 1)}
            </div>
          `;
        });
        if (comment.children.length > 2) {
          html += `
            <button 
              class="load-more-button" 
              onclick="loadMoreChildren(${comment.id}, 3)"
            >
              Розгорнути ще
            </button>
          `;
        }
        html += `</div>`;
      }

      return html;
    }

    function loadMoreChildren(parentId, count) {
      let container = document.getElementById('children-container-' + parentId);
      if (!container) return;

      let visible = parseInt(container.getAttribute('data-visible'));
      let childComments = container.getElementsByClassName('child-comment');
      let total = childComments.length;

      for (let i = visible; i < visible + count && i < total; i++) {
        childComments[i].style.display = 'block';
      }
      visible = Math.min(visible + count, total);
      container.setAttribute('data-visible', visible);

      if (visible >= total) {
        let loadMoreButton = container.querySelector('.load-more-button');
        if (loadMoreButton) {
          loadMoreButton.remove();
        }
      }
    }

    function toggleReplyForm(commentId) {
      const formDiv = document.getElementById('reply-form-' + commentId);
      formDiv.style.display = 
        (formDiv.style.display === 'none' || formDiv.style.display === '') 
          ? 'block' 
          : 'none';
    }

   
    // 2. Завантаження з бека (з сортуванням і пагінацією)
  
    function loadComments(page = 1) {
      const perPage = 25;
      const url = `/api/comments?sort_field=${currentSortField}&sort_order=${currentSortOrder}&page=${page}&per_page=${perPage}`;

      fetch(url)
        .then(response => response.json())
        .then(data => {
          let commentsHtml = '';
          data.data.forEach(comment => {
            commentsHtml += renderComment(comment);
          });
          document.getElementById('commentsList').innerHTML = commentsHtml;

          // пагінація
          renderPagination(data.meta);

          // Відображаємо актуальне сортування
          const sortInfo = `Сортування за "${currentSortField}" (${currentSortOrder})`;
          document.getElementById('currentSortInfo').textContent = sortInfo;
        })
        .catch(error => console.error('Error loading comments:', error));
    }

    function changeSort(field) {
      if (currentSortField === field) {
        // Якщо тицяємо два рази - міняємо порядок
        currentSortOrder = (currentSortOrder === 'asc') ? 'desc' : 'asc';
      } else {
        currentSortField = field;
        currentSortOrder = 'asc';
      }
      currentPage = 1;
      loadComments(currentPage);
    }

    // Пагінація
    function renderPagination(meta) {
      let container = document.getElementById('paginationControls');
      if (!meta || meta.last_page <= 1) {
        container.innerHTML = '';
        return;
      }

      let paginationHtml = '';
      // Попередня
      if (meta.current_page > 1) {
        paginationHtml += `
          <button 
            class="pagination-button" 
            onclick="goToPage(${meta.current_page - 1})"
          >
            ←
          </button>
        `;
      }
      // Кнопки сторінок
      for (let page = 1; page <= meta.last_page; page++) {
        paginationHtml += `
          <button 
            class="pagination-button ${page === meta.current_page ? 'active' : ''}"
            onclick="goToPage(${page})"
          >
            ${page}
          </button>
        `;
      }
      // Наступна
      if (meta.current_page < meta.last_page) {
        paginationHtml += `
          <button 
            class="pagination-button" 
            onclick="goToPage(${meta.current_page + 1})"
          >
            →
          </button>
        `;
      }
      container.innerHTML = paginationHtml;
    }

    function goToPage(page) {
      currentPage = page;
      loadComments(currentPage);
    }

    
    // повтор
    
    function submitReply(event, parentId) {
      event.preventDefault();
      const form = event.target;
      let formData = new FormData(form);
      formData.append('parent_id', parentId);

      const avatarUrl = document.getElementById('avatarUrlField').value;
      if (avatarUrl) {
        formData.append('avatar_url', avatarUrl);
      }
      const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

      fetch('/api/comments', {
          method: 'POST',
          headers: {
              'Accept': 'application/json',
              'X-CSRF-TOKEN': csrfToken
          },
          body: formData
      })
      .then(response => response.json())
      .then(data => {
          alert('Відповідь збережено!');
          loadComments(currentPage);
      })
      .catch(error => console.error('Error submitting reply:', error));
    }

  
    // 4. Профіль користувача
   
    function loadProfile() {
      fetch('/api/user-profile')
        .then(response => response.json())
        .then(data => {
          if (data && data.user_name) {
              document.querySelector('#profileForm input[name="user_name"]').value = data.user_name || '';
              document.querySelector('#profileForm input[name="email"]').value = data.email || '';
              document.querySelector('#profileForm input[name="home_page"]').value = data.home_page || '';

              let avatarImg = document.getElementById('profileImage');
              if (data.avatar_url) {
                  avatarImg.src = data.avatar_url;
                  document.getElementById('avatarUrlField').value = data.avatar_url;
              } else {
                  avatarImg.src = '{{ asset('images/default-profile.png') }}';
              }

              document.getElementById('commentForm').classList.remove('disabled');
              document.getElementById('commentNotification').style.display = 'none';
          } else {
              // відключення форми
              document.getElementById('commentForm').classList.add('disabled');
              document.getElementById('commentNotification').style.display = 'block';
          }
        })
        .catch(error => console.error('Error loading profile:', error));
    }

    document.getElementById('profileForm').addEventListener('submit', function(e) {
      e.preventDefault();
      let formData = new FormData(this);

      const avatarUrl = document.getElementById('avatarUrlField').value;
      if (avatarUrl) {
          formData.append('avatar_url', avatarUrl);
      }
      const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

      fetch('/api/user-profile', {
          method: 'POST',
          headers: {
              'Accept': 'application/json',
              'X-CSRF-TOKEN': csrfToken
          },
          body: formData
      })
      .then(response => response.json())
      .then(data => {
          alert('Профіль збережено!');
          loadProfile();
      })
      .catch(error => console.error('Error saving profile:', error));
    });

    document.getElementById('resetProfile').addEventListener('click', function() {
      const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
      if (confirm("Ви впевнені, що хочете скинути профіль?")) {
        fetch('/api/user-profile', {
          method: 'DELETE',
          headers: {
              'Accept': 'application/json',
              'X-CSRF-TOKEN': csrfToken
          }
        })
        .then(response => response.json())
        .then(data => {
          alert('Профіль скинуто!');
          loadProfile();
        })
        .catch(error => console.error('Error resetting profile:', error));
      }
    });

    
    // 5. Надсилання нового головного комента
 
    document.getElementById('commentForm').addEventListener('submit', function(e) {
      e.preventDefault();
      if (this.classList.contains('disabled')) {
        alert('Спочатку заповніть свій профіль!');
        return;
      }
      let formData = new FormData(this);
      const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

      fetch('/api/comments', {
          method: 'POST',
          headers: {
              'Accept': 'application/json',
              'X-CSRF-TOKEN': csrfToken
          },
          body: formData
      })
      .then(response => response.json())
      .then(data => {
          alert('Коментар збережено!');
          this.reset();
          currentPage = 1;
          loadComments(currentPage);
      })
      .catch(error => console.error('Error submitting comment:', error));
    });

  
    document.addEventListener('click', function(e) {
      if (e.target.classList.contains('comment-image')) {
        let lightbox = document.getElementById('lightbox');
        let lightboxImg = document.getElementById('lightbox-img');
        lightboxImg.src = e.target.src;
        lightbox.style.display = 'flex';
      }
    });
    document.getElementById('lightbox-close').addEventListener('click', function() {
      document.getElementById('lightbox').style.display = 'none';
    });
    document.getElementById('lightbox').addEventListener('click', function(e) {
      if (e.target === this) {
        this.style.display = 'none';
      }
    });
    document.getElementById('lightbox-img').addEventListener('click', function(e) {
      e.stopPropagation();
      this.classList.toggle('expanded');
    });

 
    // 7. Генерація аватарок через DiceBear

    document.getElementById('generateAvatarBtn').addEventListener('click', function() {
      generateRandomAvatar();
    });

    function generateRandomAvatar() {
      const randomSeed = Math.random().toString(36).substring(2);

      const topStyles = [
        'noHair', 'hat', 'hijab', 'turban', 'bigHair', 'bob', 'bun', 'curly',
        'curvy', 'dreads', 'frida', 'fro', 'froBand', 'notTooLong', 'shavedSides',
        'miaWallace', 'straight', 'straight2', 'straightStrand', 'dreads01', 'dreads02',
        'frizzle', 'shaggy', 'shaggyMullet', 'shortCurly', 'shortFlat', 'shortRound',
        'shortWaved', 'sides', 'theCaesar', 'theCaesarSidePart'
      ];
      const hairColors = [
        '000000', '654321', 'FFD700', 'FF5733', 'A52A2A'
      ];
      const skinColors = [
        'F1C27D', '8D5524', 'D1C18F', 'F9D96E', '270B0B'
      ];
      const eyeStyles  = [
        'default', 'happy', 'surprised', 'wink', 'squint', 'side'
      ];

      function randomItem(arr) {
        return arr[Math.floor(Math.random() * arr.length)];
      }

      const topStyle   = randomItem(topStyles);
      const hairColor  = randomItem(hairColors);
      const skinColor  = randomItem(skinColors);
      const eyes       = randomItem(eyeStyles);

      const avatarUrl = `https://api.dicebear.com/6.x/avataaars/svg?seed=${randomSeed}&top=${topStyle}&hairColor=${hairColor}&skinColor=${skinColor}&eyes=${eyes}`;

      console.log('Avatar URL:', avatarUrl);

      let profileImg = document.getElementById('profileImage');
      if (profileImg) {
        profileImg.src = avatarUrl;
      }
      document.getElementById('avatarUrlField').value = avatarUrl;
    }

    // 8. Початкове завантаження
    window.onload = function() {
      loadProfile();
      loadComments(); 
    };
  </script>
</body>
</html>




