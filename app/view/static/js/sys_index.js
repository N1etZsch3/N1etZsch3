function loadPagination(totalPages) {
    $('.pagination').empty(); // 清空现有分页标签
    for (let i = 1; i <= totalPages; i++) {
        const pageSpan = $('<span></span>')
            .addClass('page-number')
            .text(i)
            .click(function() {
                loadPosts(i); // 点击时加载对应页码的文章
            });
        $('.pagination').append(pageSpan);
    }
}

function getTotalPages() {
    $.get('../controller/router.php?action=get_total_pages&type=all', function(data) {
        loadPagination(parseInt(data)); // 确保数据被解析为整数
        console.log(`Total pages: ${data}`);
    });
}

// 动态加载文章预览
function loadPosts(page = 1) {
    // 清除现有的博客文章预览
    $('.post-preview-container').empty();

    // 根据页码加载文章
    $.getJSON(`../controller/router.php?action=get_posts&type=all&page=${page}`, function(data) {
        data.forEach(function(post) {
            const postDiv = $('<div></div>').addClass('post-preview').html(`
                    <h2 class="post-title">${post.title}</h2>
                    <p class="post-summary">${post.content.substring(0, 100)}...</p>
                    <a href="blog_view.php?id=${post.id}" class="read-more-link">阅读全文</a>
                `);
            $('.post-preview-container').append(postDiv);
        });
    });
}

$(document).ready(function() {
    loadPosts();// 初始加载文章和分页
    getTotalPages(); // 获取总页数
});