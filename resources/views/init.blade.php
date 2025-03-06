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
    <div class="container">
        <button class="edit-btn" onclick="openModal1('branch_add')">إضافة فرع<i class="fa fa-plus-square"></i></button>

        <button class="edit-btn" onclick="openModal1('account_add')">إضافة حساب<i class=" fa fa-plus-square"></i></button>
        <button class="edit-btn" onclick="openModal1('category_add')">إضافة صنف<i class="fa fa-plus-square"></i></button>
        <button class="edit-btn" onclick="openModal1('cashir_add')">إضافة كاشير<i class="fa fa-plus-square"></i></button>
        <button class="edit-btn" onclick="openModal1('supplier_add')">إضافة مورد<i class="fa fa-plus-square"></i></button>

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
                                <button class="edit-btn"
                                    onclick="openModal({id: '{{ $b->id }}', name: '{{ $b->branch }}'}, 'branch')">تعديل<i
                                        class="fa-solid fa-pen-to-square"></i></button>
                                <button class="delete-btn"
                                    onclick="confirmDelete({{ $b->id }},'/branch/destroy/')">حذف<i
                                        class="fa-solid fa-trash"></i></button>
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
                    <th onclick="sortTable(2, 'accounts-table')">الفرع</th>
                    <th onclick="sortTable(3, 'accounts-table')">مدين</th>
                    <th onclick="sortTable(4, 'accounts-table')">دائن</th>
                    <th onclick="sortTable(5, 'accounts-table')">الرصيد</th>
                    <th onclick="sortTable(6, 'accounts-table')">النوع</th>

                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @if (isset($data1))
                    @foreach ($data1 as $c)
                        <tr>
                            <td>{{ $c->id }}</td>
                            <td>{{ $c->account }} </td>
                            <td>
                                @foreach ($branchs as $b1)
                                    @if ($b1->id == $c->branch_id)
                                        {{ $b1->branch }}
                                    @endif
                                @endforeach
                            </td>
                            <td>{{ $c->debt }}</td>
                            <td>{{ $c->credit }}</td>
                            <td>{{ $c->balance }}</td>
                            <td>
                                @if ($c->type == 'box')
                                    صندوق
                                @else
                                    بنك
                                @endif
                            </td>

                            <td class="action-buttons">
                                <button class="edit-btn"
                                    onclick="openModal({id: '{{ $c->id }}', name: '{{ $c->account }}'}, 'account')">تعديل<i
                                        class="fa-solid fa-pen-to-square"></i></button>
                                <button class="delete-btn"
                                    onclick="confirmDelete({{ $c->id }},'/account/destroy/')">حذف<i
                                        class="fa-solid fa-trash"></i></button>
                            </td>

                        </tr>
                    @endforeach
                @endif

            </tbody>
        </table>
        <div class="pagination" id="accounts-pagination"></div>
        <!-- الكاشيرات -->
        <input type="text" class="search-input" placeholder="بحث في الكاشيرات..."
            onkeyup="searchTable(this, 'casher-table')">
        <table id="casher-table">
            <thead>
                <tr>
                    <th onclick="sortTable(0, 'casher-table')">الرقم</th>
                    <th onclick="sortTable(1, 'casher-table')">الاسم</th>
                    <th onclick="sortTable(1, 'casher-table')">الفرع</th>

                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @if (isset($casher))
                    @foreach ($casher as $c12)
                        <tr>
                            <td>{{ $c12->id }}</td>
                            <td>{{ $c12->casher }} </td>
                            
                        @foreach ($branchs as $b2 )
                        @if ($b2->id== $c12->branch_id)
                        <td>{{ $b2->branch }}</td>
                        
                        @endif
                        @endforeach


                            


                            <td class="action-buttons">
                                <button class="edit-btn"
                                    onclick="openModal({id: '{{ $c12->id }}', casher: '{{ $c12->casher }}', branch: '{{ $b2->branch}}'}, 'casher')">تعديل<i
                                        class="fa-solid fa-pen-to-square"></i></button>
                                <button class="delete-btn"
                                    onclick="confirmDelete({{ $c12->id }},'/casher/destroy/')">حذف<i
                                        class="fa-solid fa-trash"></i></button>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
        <div class="pagination" id="casher-pagination"></div>

<!-- نهاية الكاشيرات -->

<!-- الموردين -->
<input type="text" class="search-input" placeholder="بحث في الموردين..."
            onkeyup="searchTable(this, 'suppliers-table')">
        <table id="suppliers-table">
            <thead>
                <tr>
                    <th onclick="sortTable(0, 'suppliers-table')">الرقم</th>
                    <th onclick="sortTable(1, 'suppliers-table')">الاسم</th>
                    <th onclick="sortTable(2, 'suppliers-table')">المدين</th>
                    <th onclick="sortTable(3, 'suppliers-table')">الدائن</th>
                    <th onclick="sortTable(4, 'suppliers-table')">الرصيد</th>

                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @if (isset($suppliers))
                    @foreach ($suppliers as $c11)
                        <tr>
                            <td>{{ $c11->id }}</td>
                            <td>{{ $c11->supplier }} </td>
                            <td>{{ $c11->debt }}</td>
                            <td>{{ $c11->credit }}</td>
                            <td>{{ $c11->balance }}</td>
                            <td class="action-buttons">
                                <button class="edit-btn"
                                    onclick="openModal({id: '{{ $c11->id }}', name: '{{ $c11->supplier }}'}, 'supplier')">تعديل<i
                                        class="fa-solid fa-pen-to-square"></i></button>
                                <button class="delete-btn"
                                    onclick="confirmDelete({{ $c11->id }},'/supplier/destroy/')">حذف<i
                                        class="fa-solid fa-trash"></i></button>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
        <div class="pagination" id="casher-pagination"></div>




<!--                             الاصناااااااااااااااااااااف -->

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
                                <button class="edit-btn"
                                    onclick="openModal({id: '{{ $d->id }}', name: '{{ $d->item }}'}, 'category')">تعديل<i
                                        class="fa-solid fa-pen-to-square"></i></button>
                                <button class="delete-btn"
                                    onclick="confirmDelete({{ $d->id }},'/items/destroy/')">حذف<i
                                        class="fa-solid fa-trash"></i></button>
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
          <form id="editForm"  action="{{ route('branch.update') }}" method="POST">
            @csrf
            <h2>تعديل بيانات الفرع</h2>
            
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
          <form id="editForm" action="{{ route('account.update') }}" method="POST">
            @csrf
            <h2>تعديل بيانات الحساب</h2>
            
            <input type="text" id="accountId" name="id" value="${record.id}" hidden>
            <label for="accountOldName">الاسم السابق:</label>
            <input type="text" id="accountOldName" name="oldName" value="${record.name}" disabled>
            <label for="accountNewName">الاسم الجديد:</label>
            <input type="text" id="accountNewName" name="newName" placeholder="أدخل الاسم الجديد" required>
            <div class="custom-form-group half-width">
                        <label for="type">النوع:</label>
                        <select id="type" name="type" required>
                            <option value="box">صندوق</option>
                            <option value="bank">بنك</option>
                        </select>
                    </div>
            <button type="submit">حفظ التعديلات</button>
          </form>
        `;
            } else if (type === "category") {
                formHtml = `
          <form id="editForm" action="{{ route('items.update') }}" method="POST">
            @csrf
            <h2>تعديل بيانات الصنف</h2>
            <input type="text" id="categoryId" name="id" value="${record.id}" hidden>
            <label for="categoryOldName">الاسم السابق:</label>
            <input type="text" id="categoryOldName" name="oldName" value="${record.name}" disabled>
            <label for="categoryNewName">الاسم الجديد:</label>
            <input type="text" id="categoryNewName" name="newName" placeholder="أدخل الاسم الجديد" required>
            <button type="submit">حفظ التعديلات</button>
          </form>
        `;
            } else if (type === "casher") {
                formHtml = `
          <form id="editForm" action="{{ route('casher.update') }}" method="POST">
            @csrf
            <h2>تعديل بيانات الكاشير</h2>
            <input type="text" id="id" name="id" value="${record.id}" hidden>
            <label for="casherOldName">الاسم السابق:</label>
            <input type="text" id="casherOldName" name="oldName" value="${record.casher}" disabled>
            <label for="casherNewName">الاسم الجديد:</label>
            <input type="text" id="categoryNewName" name="newName" placeholder="أدخل الاسم الجديد" required>

<label for="casherOldbranch">الفرع السابق:</label>

            <input type="text" id="casherOldbranch" name="oldbranch" value="${record.branch}" disabled>
            <label for="casherNewName">الفرع الجديد:</label>
            <div class="custom-form-group half-width">
                        
                   
<select id="branch" name="branch" required>                   
                        @if (isset($branchs))
                            @foreach ($branchs as $b)
                                <option value="{{ $b->id }}">{{ $b->branch }}</option>
                            @endforeach
                        @endif
                    </select>  
                     </div>
            <button type="submit">حفظ التعديلات</button>
          </form>
        `;
            }
// تعديييلللللللل بيانات المورد
            else if (type === "supplier") {
                formHtml = `
          <form id="editForm" action="{{ route('supplier.update') }}" method="POST">
            @csrf
            <h2>تعديل بيانات المورد</h2>
            <input type="text" id="supplierid" name="id" value="${record.id}" hidden>
            <label for="supplier_oldname">الاسم السابق:</label>
            <input type="text" id="supplier_oldname" name="oldName" value="${record.name}" disabled>
            <label for="supplier_newname">الاسم الجديد:</label>
            <input type="text" id="supplier_newname" name="newName" placeholder="أدخل الاسم الجديد" required>
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

        function confirmDelete(id, pa) {
            if (confirm('هل أنت متأكد أنك تريد حذف هذا السجل؟')) {
                // إذا تم التأكيد، قم بتوجيه المستخدم إلى الراوت الخاص بالحذف
                window.location.href = pa + id;
            }
        }

        // إغلاق النافذة عند النقر على أيقونة الإغلاق أو خارج المحتوى
        closeBtn.onclick = closeModal;
        window.onclick = function(event) {
            if (event.target === modal) {
                closeModal();
            }
        };


        /**
         * دالة فتح النافذة واستدعاء النموذج المناسب بناءً على السجل والنوع.
         * @param {Object} record - يحتوي على بيانات السجل {id, name}.
         * @param {string} type - نوع الكيان ("branch", "account", "category").
         */
        function openModal1(type) {
            let formHtml = "";
            if (type === "branch_add") {
                formHtml = `
                <h3>إضافة فرع</h3>
          <form method="POST" action="{{ route('branch.store') }}">
                @csrf
                <div class="form-group">
                    <label for="branch_name">اسم الفرع</label>
                    <input type="text" id="branch_name" name="branch_name" required>
                    <button type="submit">إضافة</button>
                </div>
            </form>
        `;
            } else if (type === "account_add") {
                formHtml = `
            <h3>إضافة حساب</h3>
            <form action="{{ route('account.store') }}" method="POST">
                @csrf
                <div class="form-group">
                <div class="custom-form-group third-width">
                    <label for="casher">اسم الحساب</label>                    
                    <input type="text" id="account" name="account_name" required>
                    <label for="branch">الفرع:</label>
                    <select id="branch" name="branch" required>                   
                        @if (isset($branchs))
                            @foreach ($branchs as $bc)
                                <option value="{{ $bc->id }}">{{ $bc->branch }}</option>
                            @endforeach
                        @endif
                    </select>  
                      <label for="branch">نوع الحساب:</label>

                    <select id="type" name="type" required>
                    <option value="box">صندوق</option>
                  <option value="bank">بنك</option>
</select>  
                    </div>                    
                </div>
                <div class="custom-form-group third-width">
                    <button type="submit">إضافة</button>
                    </div>
            </form>
        `;
            } else if (type === "category_add") {
                formHtml = `
           <h3>إضافة صنف</h3>
            <form action="{{ route('items.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="category-name">اسم الصنف</label>
                    <input type="text" id="category-name" name="category_name" required>
                    <button type="submit">إضافة</button>
                </div>
            </form>
        `;
            } else if (type === "cashir_add") {
                formHtml = `
           <h3>إضافة كاشير</h3>
            <form action="{{ route('casher.store') }}" method="POST">
                @csrf
                <div class="form-group">
                <div class="custom-form-group third-width">
                    <label for="casher">اسم الكاشير</label>
                    
                    <input type="text" id="casher" name="casher" required>
                    <label for="branch">الفرع:</label>
                    <select id="branch" name="branch" required>
                    
                        @if (isset($branchs))
                            @foreach ($branchs as $b)
                                <option value="{{ $b->id }}">{{ $b->branch }}</option>
                            @endforeach
                        @endif
                    </select>                 
                    </div>
                    
                </div>
                <div class="custom-form-group third-width">
                    <button type="submit">إضافة</button>
                    </div>
            </form>
        `;
            }
            else if (type === "supplier_add") {
                formHtml = `
           <h3>إضافة مورد</h3>
            <form action="{{ route('supplier.store') }}" method="POST">
                @csrf
                <div class="form-group">
                <div class="custom-form-group third-width">
                    <label for="supplier">اسم المورد</label>
                    
                    <input type="text" id="supplier" name="supplier" required>
                    <label for="debt">المدين:</label>
                     <input type="flaot" id="debt" name="debt" required>
          <label for="credit">الدائن:</label>
                     <input type="flaot" id="credit" name="credit" required>
                    </div>
                    
                </div>
                <div class="custom-form-group third-width">
                    <button type="submit">إضافة</button>
                    </div>
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


        // دالة إظهار رسالة التوست في أعلى الشاشة ثم إخفاؤها تلقائيًا بعد 2 ثانية



        // إغلاق النافذة عند النقر على أيقونة الإغلاق أو خارج المحتوى
    </script>






@endsection
