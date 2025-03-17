@extends('include.app')
@section('title')
    التقارير
@endsection

@section('page')
    تقارير
@endsection
<style>
    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.6);
        padding-top: 60px;
    }

    .modal-content {
        background-color: #fff;
        margin: 5% auto;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        width: 80%;
        max-width: 500px;
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .close:hover,
    .close:focus {
        color: #000;
        text-decoration: none;
    }

    button {
        padding: 10px 20px;
        font-size: 16px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    button:hover {
        background-color: #0056b3;
    }

    input[type="text"],
    input[type="password"],
    input[type="email"],
    input[type="hidden"] {
        width: 100%;
        padding: 10px;
        margin: 8px 0;
        box-sizing: border-box;
        border: 1px solid #ccc;
        border-radius: 4px;
    }





    input[disabled] {
        background-color: #f2f2f2;
        cursor: not-allowed;
    }

    #toast {
        visibility: hidden;
        min-width: 250px;
        max-width: 90%;
        background-color: #444;
        color: #fff;
        text-align: center;
        border-radius: 4px;
        padding: 16px 20px;
        position: fixed;
        z-index: 999;
        left: 50%;
        top: 20px;
        transform: translateX(-50%);
        font-size: 16px;
        opacity: 0;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        line-height: 1.4;
        transition: opacity 0.5s ease, visibility 0.5s ease;
    }

    #toast.show {
        visibility: visible;
        opacity: 1;
    }

    i {
        margin-right: 10px
    }
</style>
@section('main')
    @if (session('success'))
        <div class="alert alert-success" id="success-alert">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger" id="error-alert">
            {{ session('error') }}
        </div>
    @endif

    <!-- resources/views/users/index.blade.php -->

    <h1>قائمة المستخدمين</h1>
    <button onclick="openModal1('user_add')">إضافة مستخدم جديد</button>
    <br></br>
    <table>
        <thead>
            <tr>
                <th>الاسم</th>
                <th>البريد الإلكتروني</th>
                <th>خيارات</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>

                    <td class="action-buttons">
                        <button class="edit-btn"
                            onclick="openModal({id: '{{ $user->id }}', name: '{{ $user->name }}',email:'{{ $user->email }}'}, 'user')">تعديل<i
                                class="fa-solid fa-pen-to-square"></i></button>

                        <button class="delete-btn" onclick="confirmDelete({{ $user->id }},'/users/destroy/')">حذف<i
                                class="fa-solid fa-trash"></i></button>


                    </td>



                </tr>
            @endforeach
        </tbody>
    </table>

    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <div id="modalFormContent"></div>
        </div>
    </div>



    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <!-- سيتم تحميل النموذج المناسب هنا -->
            <div id="modalFormContent"></div>
        </div>
    </div>

    <!-- عنصر التوست (Toast) الذي سيظهر في أعلى الشاشة -->
    <div id="toast"></div>

    @if (session('status'))
        <div style="color: green;">{{ session('status') }}</div>
    @elseif (session('error'))
        <div style="color: red;">{{ session('error') }}</div>
    @endif
    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <script>
        const modal = document.getElementById("myModal");
        const closeBtn = document.querySelector(".close");
        const modalFormContent = document.getElementById("modalFormContent");

        /**
         * دالة فتح النافذة واستدعاء النموذج المناسب لتعديل بيانات المستخدم.
         * @param {Object} user - يحتوي على بيانات المستخدم {id, name, email}.
         */
        function openModal(user) {
            const formHtml = `
            <form id="editForm" action="/users/update/${user.id}" method="POST">
                @csrf
                <h2>تعديل بيانات المستخدم</h2>
                <label for="userName">الاسم:</label>
                <input type="text" id="userName" name="name" value="${user.name}" required>
                <label for="userEmail">البريد الإلكتروني:</label>
                <input type="email" id="userEmail" name="email" value="${user.email}" required>
                <label for="userPassword">كلمة المرور (اختياري):</label>
                <input type="password" id="userPassword" name="password">
                <label for="userPasswordConfirmation">تأكيد كلمة المرور:</label>
                <input type="password" id="userPasswordConfirmation" name="password_confirmation">
                <button type="submit">حفظ التعديلات</button>
            </form>
        `;
            modalFormContent.innerHTML = formHtml;
            modal.style.display = "block";
        }

        // دالة إغلاق النافذة
        function closeModal() {
            modal.style.display = "none";
            modalFormContent.innerHTML = "";
        }

        // دالة إظهار رسالة التوست
        function showToast(message) {
            const toast = document.getElementById("toast");
            toast.innerText = message;
            toast.style.display = "block";
            setTimeout(() => {
                toast.style.display = "none";
            }, 2000);
        }

        // إضافة حدث الإغلاق للنافذة
        closeBtn.onclick = closeModal;
        window.onclick = function(event) {
            if (event.target === modal) {
                closeModal();
            }
        };



        function openModal1(type) {
            let formHtml = "";
            if (type === "user_add") {
                formHtml = `
            <h3>إضافة مستخدم جديد</h3>
            <form method="POST" action="{{ route('users.store') }}">
                @csrf
                <div class="form-group">
                    <label for="user_name">الاسم</label>
                    <input type="text" id="user_name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="user_email">البريد الإلكتروني</label>
                    <input type="email" id="user_email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="user_password">كلمة المرور</label>
                    <input type="password" id="user_password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="user_password_confirmation">تأكيد كلمة المرور</label>
                    <input type="password" id="user_password_confirmation" name="password_confirmation" required>
                </div>
                <button type="submit">إضافة</button>
            </form>
        `;
            }

            // تعبئة المودال بالمحتوى وعرضه
            const modalFormContent = document.getElementById("modalFormContent");
            modalFormContent.innerHTML = formHtml;
            const modal = document.getElementById("myModal");
            modal.style.display = "block";
        }

        // إغلاق المودال عند النقر على زر الإغلاق
        function closeModal() {
            const modal = document.getElementById("myModal");
            modal.style.display = "none";
            const modalFormContent = document.getElementById("modalFormContent");
            modalFormContent.innerHTML = "";
        }



        function confirmDelete(id, pa) {
            if (confirm('هل أنت متأكد أنك تريد حذف هذا السجل؟')) {
                // إذا تم التأكيد، قم بتوجيه المستخدم إلى الراوت الخاص بالحذف
                window.location.href = pa + id;
            }
        }
    </script>





@endsection
