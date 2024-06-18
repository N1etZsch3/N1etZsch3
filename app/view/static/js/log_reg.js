$(document).ready(function() {
    // 登录表单验证
    $('#login-form').submit(function(e) {
        e.preventDefault();
        const email = $('#login-email').val().trim();
        const password = $('#login-password').val();

        if (!email || !password) {
            alert('用户名和密码不能为空！');
            return false;
        }

        if (!validatePassword(password)) {
            alert('密码格式不正确，密码必须包含字母和数字，且长度为5到18个字符。');
            return false;
        }

        // 如果通过验证，可以提交表单数据
        // 使用 $.post 发送数据
        $.post('../controller/router.php', { email: email, password: password, action: "user_login"}, function(data) {
            console.log(data); // 可以根据返回的数据进行处理
            if (data === 'login-pass') {
                location.href = 'sys_index.php';
            }
            else {
                alert('用户名或密码错误！');
                return false;
            }
        });
    });

    // 注册表单验证
    $('#register-form').submit(function(e) {
        e.preventDefault();
        const username = $('#register-username').val().trim();
        const email = $('#register-email').val().trim();
        const password = $('#register-password').val();

        if (!username || !email || !password) {
            alert('所有字段都是必填项！');
            return false;
        }

        if (!validateUsername(username)) {
            alert('用户名格式不正确，用户名必须至少有3个字符且不能以数字开头。');
            return false;
        }

        if (!validateEmail(email)) {
            alert('邮箱格式不正确。');
            return false;
        }

        if (!validatePassword(password)) {
            alert('密码格式不正确，密码必须包含字母和数字，且长度为5到18个字符。');
            return false;
        }

        // 如果通过验证，可以提交表单数据
        // 使用 $.post 发送数据
        $.post('../controller/router.php', { username: username, email: email, password: password, action: "user_register" }, function(data) {
            console.log(data); // 可以根据返回的数据进行处理
            if (data === 'register-pass') {
                location.href = 'login.php';
            }
            else {
                alert('注册失败！');
                return false;
            }
        });
    });

    function validateUsername(username) {
        const re = /^[A-Za-z][A-Za-z0-9]{2,}$/;
        return re.test(username);
    }

    function validatePassword(password) {
        const re = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{5,18}$/;
        return re.test(password);
    }

    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }
});
