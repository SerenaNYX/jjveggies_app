@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center">Contact Us</h1>
    
    <div class="contact-section">
        
        <div class="contact-info">
            <h3>J&J Vegetables Marketing</h3>
            <p>9, Jalan Permas 3/18,<br>
            Bandar Baru Permas Jaya,<br>
            81750 Masai, Johor.</p>
            <p>Tel: +607-366 5191<br>
                Fax: +607-388 2034<br>
            Email: jnjvege@gmail.com</p>
            <hr>
            <a href="{{ route('enquiries.index') }}" class="btn">View your enquiries</a>
        </div>
    
        <div class="contact-form">
            <h2>Send us a message</h2>
            <form action="{{ route('contact.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" value="{{ Auth::user()->name }}" required>
                </div>
                
                <div class="form-group">
                    <label for="contact">Contact Number:</label>
                    <input type="tel" name="phone" id="phone" class="form-control" maxlength="12" required oninput="validateContact(this)" required>
                    <script>
                        function validateContact(input) {
                            input.value = input.value.replace(/[^0-9]/g, '');
                        }
                    </script>
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address:</label>
                    <input type="email" id="email" name="email" value="{{ Auth::user()->email }}" required>
                </div>
                
                <div class="form-group">
                    <label for="message">Message:</label>
                    <textarea id="message" name="message" rows="5" required></textarea>
                </div>
                
                <div class="form-group">
                    <label for="attachments">Attachments (optional):</label>
                    <input type="file" id="attachments" name="attachments[]" multiple>
                    <small class="form-text text-muted">You can upload multiple files (max 10MB each, allowed types: jpg, png, pdf, doc)</small>
                </div>

                <button type="submit" class="btn">Submit Enquiry</button>
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>

<style>
    .contact-section {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 3rem;
    }
    
    .contact-info {
   /*     min-width: 60px;*/
        background: #f9f9f9;
        padding: 1rem;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .contact-form {
        flex: 1;
        background: #fff;
        padding: 1.5rem;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: bold;
    }
    
    input, textarea {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 1rem;
    }

    #message {
        resize: none;
    }
    
</style>
@endsection