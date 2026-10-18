/**
 * auth.js — Toggle hiện/ẩn mật khẩu
 * Vanilla JS thuần, không dùng jQuery.
 * Tìm tất cả button.toggle-password trên trang và gắn sự kiện click.
 */
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.toggle-password').forEach(function (btn) {
        btn.addEventListener('click', function () {
            // Tìm input trong cùng .auth-input-wrapper
            var wrapper = btn.closest('.auth-input-wrapper');
            var input   = wrapper ? wrapper.querySelector('.auth-input') : null;
            if (!input) return;

            // Đổi type giữa password ↔ text
            var isHidden = input.type === 'password';
            input.type   = isHidden ? 'text' : 'password';

            // Đổi icon eye / eye-slash
            var icon = btn.querySelector('i');
            if (icon) {
                icon.classList.toggle('fa-eye',       !isHidden);
                icon.classList.toggle('fa-eye-slash',  isHidden);
            }

            // Giữ focus cho UX tốt hơn
            input.focus();
        });
    });
});
