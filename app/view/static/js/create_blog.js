$(document).ready(function() {
    $('#blog-form').on('submit', function(e) {
        e.preventDefault(); // 阻止表单的默认提交行为

        const title = $('#post-title-input').val().trim();
        const content = $('#post-content-input').val().trim();

        if (title && content) {
            // 创建新博客条目的HTML结构
            const postHtml = '<div class="post">' +
                '<h3>' + title + '</h3>' +
                '<p>' + content + '</p>' +
                '</div>';

            // 将新博客条目添加到博客列表
            $('#posts-section').append(postHtml);

            // 清空表单输入字段
            $('#blog-form').find('input[type="text"], textarea').val('');
        }
        else {
            // 弹出错误消息
            alert('标题和内容不能为空！');
            return false;
        }

        $.post('../controller/router.php', { title: title, content: content, action: "create_blog"}, function(data) {
            console.log(data); // 可以根据返回的数据进行处理
            if (data !== 'create-pass') {
                alert('发表博客失败！');
                location.href = 'user_submit.php'
            }
            else {
                alert('发表博客成功！');
            }
        });
    });
});
