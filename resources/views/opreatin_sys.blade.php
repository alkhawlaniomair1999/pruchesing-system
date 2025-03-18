@extends('include.app')
@section('title')
    المستخدمين
@endsection

@section('page')
    إدارة المستخدمين
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

    <h1>قائمة عمليات المستخدمين على النظام</h1>
    
    <br></br>
    <table>
        <thead>
            <tr>
                <th>رقم</th>
                <th>نوع العملية </th>
                <th>التفاصيل  </th>
                <th> المستخدم </th>
                <th> الوقت </th>

            </tr>
        </thead>
        <tbody>
            @foreach ($opreations as $op)
                <tr>
                    <td>{{ $op->id }}</td>
                    <td>{{ $op->operation_type }}</td>
                    <td>{{ $op->details }}</td>
                    @if (isset($users))
                    @foreach ($users as $user)
@if ($user->id == $op->user_id)
<td>{{ $user->name }}</td>
@endif
                    @endforeach
                    @endif
                    
                    <td>{{ $op->created_at }}</td>

                
                </tr>
            @endforeach
        </tbody>
    </table>

   



    

    <!-- عنصر التوست (Toast) الذي سيظهر في أعلى الشاشة -->
    

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


    





@endsection
