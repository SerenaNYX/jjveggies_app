<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Employee Login</title>

    <style>
        body {
            font-family: 'Roboto', Arial, sans-serif;
            font-size: 18px;
            font-weight: 300;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            border: 2px solid #ddd;
            border-radius: 10px;
            max-width: 450px;
            margin: 40px auto 40px;
            padding: 0 20px 35px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .btn {
            display: inline-block;
            padding: 10px 30px;
            font-size: 16px;
            color: #fff;
            background-color: #589a4b;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #5a9219;
        }

        .dashboard-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background-color: #44684a;
            color: white;
            padding: 1rem;
            width: 100%;
            .jj-logo { 
                margin-top: 8px;
                border-radius: 50%;
                width: 100px;
            }
        }

    </style>
</head>
<body>
    <div class="dashboard-container">
        <img class="jj-logo" src="{{ asset('img/logo.jpg') }}" alt="J&J Vegetables">
        <h1>J&J Vegetables</h1>
    </div>

    <div class="container">
        <h1>Employee Login</h1>
        <form action="{{ route('admin.login.post') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" required>
                @error('email')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control" required>
                @error('password')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>
</body>
</html>

