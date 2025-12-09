<script>
// SCRIPT SAO HOÀN HẢO - CHỈ 1 BỘ SAO, ĐẸP, MƯỢT, KHÔNG LỖI
document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector('.review-section form');
    if (!form) return;

    const starsContainer = form.querySelector('.stars');
    const stars = starsContainer.querySelectorAll('i');
    const hiddenInput = document.getElementById('selected-rating');

    stars.forEach((star, index) => {
        const value = index + 1;

        // Hover: đổi màu vàng tạm thời
        star.addEventListener('mouseenter', () => {
            stars.forEach((s, i) => {
                if (i <= index) {
                    s.className = 'fas fa-star';
                    s.style.color = '#ee2d2d';
                } else {
                    s.className = 'far fa-star';
                    s.style.color = '#ddd';
                }
            });
        });

        // Click: lưu và giữ sao vàng
        star.addEventListener('click', () => {
            hiddenInput.value = value;
            stars.forEach((s, i) => {
                s.className = i < value ? 'fas fa-star' : 'far fa-star';
                s.style.color = i < value ? '#ee2d2d' : '#ddd';
            });
        });
    });

    // Khi rời chuột → giữ lại sao đã chọn
    starsContainer.addEventListener('mouseleave', () => {
        const selected = hiddenInput.value || 0;
        stars.forEach((s, i) => {
            s.className = i < selected ? 'fas fa-star' : 'far fa-star';
            s.style.color = i < selected ? '#ee2d2d' : '#ddd';
        });
    });
});
</script>