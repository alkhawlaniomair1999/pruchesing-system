@extends('include.app')
@section('title')
    التهيئة
@endsection

@section('page')
    التهيئة
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
        background-color: rgba(0,0,0,0.6);
        padding-top: 60px;
    }

    .modal-content {
        background-color: #fff;
        margin: 5% auto;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
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

    input[type="text"], input[type="hidden"] {
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
      box-shadow: 0 2px 6px rgba(0,0,0,0.2);
      line-height: 1.4;
      transition: opacity 0.5s ease, visibility 0.5s ease;
    }
    #toast.show {
      visibility: visible;
      opacity: 1;
    }



</style>
@section('main')
    <div class="container">
        <div class="form-container">
            <h3>إضافة فرع</h3>
            <form method="POST" action="{{route('branch.store')}}">
                @csrf
                <div class="form-group">
                    <label for="branch_name">اسم الفرع</label>
                    <input type="text" id="branch_name" name="branch_name" required>
                    <button type="submit">إضافة</button>
                </div>
            </form>
        </div>

        <div class="form-container">
            <h3>إضافة حساب</h3>
            <form method="POST" action="{{route('account.store')}}">
                @csrf
                
                <div class="form-group">
                    <label for="account_name">اسم الحساب</label>
                    <input type="text" id="account_name" name="account_name" required>
                    <button type="submit">إضافة</button>
                </div>
            </form>
        </div>

        <div class="form-container">
            <h3>إضافة صنف</h3>
            <form action="{{ route('items.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="category-name">اسم الصنف</label>
                    <input type="text" id="category-name" name="category_name" required>
                    <button type="submit">إضافة</button>
                </div>
            </form>
        </div>
    </div>

    <div class="tables-container">
        <input type="text" class="search-input" placeholder="بحث في الفروع..."
            onkeyup="searchTable(this, 'branches-table')">
        <table id="branches-table">
            <thead>
                <tr>
                    <th onclick="sortTable(0, 'branches-table')">الرقم</th>
                    <th onclick="sortTable(1, 'branches-table')">الفروع</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @if (isset($branchs))
                    @foreach ($branchs as $b)
                        <tr>
                            <td>{{ $b->id }}</td>
                            <td>{{ $b->branch }} </td>
                            <td class="action-buttons">
                                <button class="edit-btn"onclick="openModal({id: '{{$b->id}}', name: '{{ $b->branch }}'}, 'branch')">تعديل<i class="fa-solid fa-pen-to-square"></i></button>
                                <button class="delete-btn">حذف<i class="fa-solid fa-trash"></i></button>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
        <div class="pagination" id="branches-pagination"></div>

        <input type="text" class="search-input" placeholder="بحث في الحسابات..."
            onkeyup="searchTable(this, 'accounts-table')">
        <table id="accounts-table">
            <thead>
                <tr>
                    <th onclick="sortTable(0, 'accounts-table')">الرقم</th>
                    <th onclick="sortTable(1, 'accounts-table')">الحسابات</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @if (isset($data1))
                @foreach ($data1 as $c)
                    <tr>
                        <td>{{ $c->id }}</td>
                        <td>{{ $c->account }} </td>
                        <td class="action-buttons">
                            <button class="edit-btn">تعديل<i class="fa-solid fa-pen-to-square"></i></button>
                            <button class="delete-btn">حذف<i class="fa-solid fa-trash"></i></button>
                        </td>
                    </tr>
                @endforeach
            @endif

            </tbody>
        </table>
        <div class="pagination" id="accounts-pagination"></div>

        <input type="text" class="search-input" placeholder="بحث في الأصناف..."
            onkeyup="searchTable(this, 'categories-table')">
        <table id="categories-table">
            <thead>
                <tr>
                    <th onclick="sortTable(0, 'categories-table')">الرقم</th>
                    <th onclick="sortTable(1, 'categories-table')">الأصناف</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @if (isset($data))
                    @foreach ($data as $d)
                        <tr>
                            <td>{{ $d->id }}</td>
                            <td>{{ $d->item }} </td>
                            <td class="action-buttons">
                                <button class="edit-btn">تعديل<i class="fa-solid fa-pen-to-square"></i></button>
                                <button class="delete-btn">حذف<i class="fa-solid fa-trash"></i></button>
                            </td>
                        </tr>
                    @endforeach
                @endif

            </tbody>
        </table>
        <div class="pagination" id="categories-pagination"></div>
    </div>
    </div>

   




<!-- النافذة المنبثقة (Modal) -->
<div id="myModal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <!-- سيتم تحميل النموذج المناسب هنا -->
      <div id="modalFormContent"></div>
    </div>
  </div>

  <!-- عنصر التوست (Toast) الذي سيظهر في أعلى الشاشة -->
  <div id="toast"></div>

  <script>
    const modal = document.getElementById("myModal");
    const closeBtn = document.querySelector(".close");
    const modalFormContent = document.getElementById("modalFormContent");

    /**
     * دالة فتح النافذة واستدعاء النموذج المناسب بناءً على السجل والنوع.
     * @param {Object} record - يحتوي على بيانات السجل {id, name}.
     * @param {string} type - نوع الكيان ("branch", "account", "category").
     */
    function openModal(record, type) {
      let formHtml = "";
      if (type === "branch") {
        formHtml = `
          <form id="editForm"  action="{{route('branch.update') }}" method="POST">
            @csrf
            <h2>تعديل بيانات الفرع</h2>
            <label for="branchId">ID:</label>
            <input type="number" id="branchId" name="id" value="${record.id}" hidden>
            <label for="branchOldName">الاسم السابق:</label>
            <input type="text" id="branchOldName" name="oldName" value="${record.name}" disabled>
            <label for="branchNewName">الاسم الجديد:</label>
            <input type="text" id="branchNewName" name="newName" placeholder="أدخل الاسم الجديد" required>
            <button type="submit">حفظ التعديلات</button>
          </form>
        `;
      } else if (type === "account") {
        formHtml = `
          <form id="editForm">
            <h2>تعديل بيانات الحساب</h2>
            <label for="accountId">ID:</label>
            <input type="text" id="accountId" name="id" value="${record.id}" disabled>
            <label for="accountOldName">الاسم السابق:</label>
            <input type="text" id="accountOldName" name="oldName" value="${record.name}" disabled>
            <label for="accountNewName">الاسم الجديد:</label>
            <input type="text" id="accountNewName" name="newName" placeholder="أدخل الاسم الجديد" required>
            <button type="submit">حفظ التعديلات</button>
          </form>
        `;
      } else if (type === "category") {
        formHtml = `
          <form id="editForm">
            <h2>تعديل بيانات الصنف</h2>
            <label for="categoryId">ID:</label>
            <input type="text" id="categoryId" name="id" value="${record.id}" disabled>
            <label for="categoryOldName">الاسم السابق:</label>
            <input type="text" id="categoryOldName" name="oldName" value="${record.name}" disabled>
            <label for="categoryNewName">الاسم الجديد:</label>
            <input type="text" id="categoryNewName" name="newName" placeholder="أدخل الاسم الجديد" required>
            <button type="submit">حفظ التعديلات</button>
          </form>
        `;
      }
      modalFormContent.innerHTML = formHtml;
      modal.style.display = "block";

    // //   معالجة حدث إرسال النموذج، مع إخفاء النافذة فوراً وإظهار التوست بعد تأخير بسيط
    //   document.getElementById("editForm").addEventListener("submit", function(e) {
    //     // e.preventDefault();
    //     const formData = new FormData(this);
    //     let message = "تم حفظ التعديلات:\n";
    //     for (const [key, value] of formData.entries()) {
    //       message += `${key}: ${value}\n`;
    //     }
    //     closeModal();
    //     setTimeout(() => {
    //       showToast(message);
    //     }, 100);
    //   }, { once: true });
    }

    // دالة إغلاق النافذة
    function closeModal() {
      modal.style.display = "none";
      modalFormContent.innerHTML = "";
    }

    // دالة إظهار رسالة التوست في أعلى الشاشة ثم إخفاؤها تلقائيًا بعد 2 ثانية
    function showToast(message) {
      const toast = document.getElementById("toast");
      toast.innerText = message;
      toast.classList.add("show");
      setTimeout(() => {
        toast.classList.remove("show");
      }, 2000);
    }

    // إغلاق النافذة عند النقر على أيقونة الإغلاق أو خارج المحتوى
    closeBtn.onclick = closeModal;
    window.onclick = function(event) {
      if (event.target === modal) {
        closeModal();
      }
    };
  </script>






@endsection
