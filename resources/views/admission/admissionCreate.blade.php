
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{config('app.name', 'TMCC-Esystem')}}</title>
    <link rel="shortcut icon" href="{{ asset('img/RoundLogo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/admission/admissionCreate.css') }}">
</head>
<body>
    <div class="admission-form">
    <a href="{{ url('/') }}">
        <img src="{{ asset('img/RoundLogo.png') }}" alt="Logo">
    </a>
    <div class="header-text">
    <center><h1><span>Welcome</span> to Online Admission Portal</h1></center>
    <p>To start your application, please sign up using a valid email address.</p>
    </div>


    @if(session('success'))
        <p class="success-message">{{ session('success') }}</p>
    @endif

    <form action="{{ route('admission.store') }}" method="POST">
        @csrf
        <div class="form-grid">
            
            <div class="form-group full-width">
                <label>First Name <span style="color: red">*</span></label>
                <input type="text" 
                    name="first_name" 
                    value="{{ old('first_name') }}" 
                    placeholder="First Name"
                    required>

                @error('first_name') 
                    <p class="error">{{ $message }}</p> 
                @enderror
            </div>

            <div class="form-group full-width">
                <label>Middle Name 
                    
                    
                </label>
                
                <input type="text" 
                    name="middle_name" 
                    id="middle_name" 
                    value="{{ old('middle_name') }}" 
                    placeholder="Enter your middle name. Ex. Juan">
            </div>

            <div class="form-group full-width">
                <label>Last Name <span style="color: red">*</span></label>
                <input type="text" 
                    name="last_name" 
                    value="{{ old('last_name') }}" 
                    placeholder="Last Name"
                    required>

                @error('last_name') 
                    <p class="error">{{ $message }}</p> 
                @enderror
            </div>

            <div class="form-group full-width">
                <label>Suffix 
                    
                    
                </label>
                <input type="text" 
                    name="suffix" 
                    id="suffix" 
                    value="{{ old('suffix') }}" 
                    placeholder="Enter your suffix. Ex. Jr, III">
            </div>

            <div class="form-group full-width">
                <label>Email <span style="color: red">*</span></label>
                <input type="email" 
                    name="email" 
                    value="{{ old('email') }}"
                    placeholder="Email Address"
                    required>
                    
                @error('email') 
                    <p class="error">{{ $message }}</p> 
                @enderror
            </div>

            <div class="form-group full-width">
                <label>Phone Number <span style="color: red">*</span></label>
                <input type="text" 
                        name="phone" 
                        id="phone"
                        maxlength="11"
                        pattern="09[0-9]{9}"
                        inputmode="numeric"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                        placeholder="09123456789"
                        value="{{ old('phone') }}"
                        required>

                @error('phone')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>
        
        

            <div class="form-group">
                <label>Street <span style="color: red">*</span></label>
                <input type="text" 
                    name="street" 
                    value="{{ old('street') }}" 
                    placeholder="House No., Street Name"
                    required>

                @error('street') 
                    <p class="error">{{ $message }}</p> 
                @enderror
            </div>

            <div class="form-group">
                <label>Barangay <span style="color: red">*</span></label>
                <input type="text" 
                    name="barangay" 
                    value="{{ old('barangay') }}" 
                    placeholder="Enter here"
                    required>

                @error('barangay') 
                    <p class="error">{{ $message }}</p> 
                @enderror
            </div>

            <div class="form-group">
                <label>Municipality <span style="color: red">*</span></label>
                <input type="text" 
                    name="municipality" 
                    value="{{ old('municipality') }}" 
                    placeholder="Enter here"
                    required>

                @error('municipality') 
                    <p class="error">{{ $message }}</p> 
                @enderror
            </div>

            <div class="form-group">
                <label>City <span style="color: red">*</span></label>
                <input type="text" 
                    name="city" 
                    value="{{ old('city') }}" 
                    placeholder="Enter here"
                    required>
                @error('city') 
                    <p class="error">{{ $message }}</p> 
                @enderror
            </div>

        
            <div class="form-group full-width">
                <label>Birthdate <span style="color: red">*</span></label>
                <input type="date" 
                    name="birthdate" 
                    value="{{ old('birthdate') }}">

                @error('birthdate') 
                    <p class="error">{{ $message }}</p> 
                @enderror
            </div>

            <div class="form-group full-width">
                <label>Sex <span style="color: red"> *</span></label>
                <select name="gender">
                    <option value="" disabled {{ old('gender') ? '' : 'selected' }}>-- Select Sex --</option>
                    <option value="Male" {{ old('gender')=='Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ old('gender')=='Female' ? 'selected' : '' }}>Female</option>
                </select>

                @error('gender') 
                    <p class="error">{{ $message }}</p> 
                @enderror
            </div>

            <div class="form-group full-width">
                <label><strong>Senior High School Information</strong></label>
            </div>

            <div class="form-group full-width">
                <label>Senior High School Attended <span style="color: red">*</span></label>
                <input type="text" 
                    name="shs_school" 
                    value="{{ old('shs_school') }}" 
                    placeholder="Enter Senior High School Name"
                    required>

                @error('shs_school') 
                    <p class="error">{{ $message }}</p> 
                @enderror
            </div>

            <div class="form-group full-width">
                <label>Senior High School Course / Strand <span style="color: red">*</span></label>
                <input type="text" 
                    name="shs_course" 
                    value="{{ old('shs_course') }}" 
                    placeholder="e.g. STEM, ABM, HUMSS"
                    required>

                @error('shs_course') 
                    <p class="error">{{ $message }}</p> 
                @enderror
            </div>

            <div class="form-group full-width">
                <label>General Weighted Average (GWA) <span style="color: red">*</span></label>
                <input type="number" 
                    name="gwa" 
                    step="0.01"
                    min="70"
                    max="100"
                    value="{{ old('gwa') }}" 
                    placeholder="e.g. 90.50"
                    required>

                @error('gwa') 
                    <p class="error">{{ $message }}</p> 
                @enderror
            </div>

            <div class="form-group full-width">
                <label><strong>Desired Course</strong></label>
                <label><small>Primary Course <span style="color: red"> *</span></small></label>
                    <select name="primary_course" required>
                        <option value="">Select Primary Course</option>
                        <option value="BPA" {{ old('course')=='Bachelor of Public Administration' ? 'selected' : '' }}>Bachelor of Public Administration</option>
                        <option value="BSE" {{ old('course')=='Bachelor of Science in Entrepreneurship' ? 'selected' : '' }}>Bachelor of Science in Entrepreneurship</option>
                        <option value="BSCA" {{ old('course')=='Bachelor of Science in Customs Administration' ? 'selected' : '' }}>Bachelor of Science in Customs Administration</option>
                        <option value="BSHM" {{ old('course')=='Bachelor of Science in Hospitality Management' ? 'selected' : '' }}>Bachelor of Science in Hospitality Management</option>
                        <option value="BSC18" {{ old('course')=='Bachelor of Science in Criminology (Revised 2018)' ? 'selected' : '' }}>Bachelor of Science in Criminology (Revised 2018)</option>
                        <option value="BSC19" {{ old('course')=='Bachelor of Science in Criminology (Revised 2019)' ? 'selected' : '' }}>Bachelor of Science in Criminology (Revised 2019)</option>
                        <option value="BSEMEng" {{ old('course')=='Bachelor of Secondary Education Major in English' ? 'selected' : '' }}>Bachelor of Secondary Education Major in English</option>
                        <option value="BSEMMath" {{ old('course')=='Bachelor of Secondary Education Major in Mathematics' ? 'selected' : '' }}>Bachelor of Secondary Education Major in Mathematics</option>
                        <option value="BSEMFil" {{ old('course')=='Bachelor of Secondary Education Major in Filipino' ? 'selected' : '' }}>Bachelor of Secondary Education Major in Filipino</option>
                        <option value="BSEMSocStud" {{ old('course')=='Bachelor of Secondary Education Major in Social Studies' ? 'selected' : '' }}>Bachelor of Secondary Education Major in Social Studies</option>
                    </select>

                <label><small>Secondary Course </small></label>
                    <select name="secondary_course" required>
                        <option value="">Select Secondary Course</option>
                        <option value="BPA" {{ old('course')=='Bachelor of Public Administration' ? 'selected' : '' }}>Bachelor of Public Administration</option>
                        <option value="BSE" {{ old('course')=='Bachelor of Science in Entrepreneurship' ? 'selected' : '' }}>Bachelor of Science in Entrepreneurship</option>
                        <option value="BSCA" {{ old('course')=='Bachelor of Science in Customs Administration' ? 'selected' : '' }}>Bachelor of Science in Customs Administration</option>
                        <option value="BSHM" {{ old('course')=='Bachelor of Science in Hospitality Management' ? 'selected' : '' }}>Bachelor of Science in Hospitality Management</option>
                        <option value="BSC18" {{ old('course')=='Bachelor of Science in Criminology (Revised 2018)' ? 'selected' : '' }}>Bachelor of Science in Criminology (Revised 2018)</option>
                        <option value="BSC19" {{ old('course')=='Bachelor of Science in Criminology (Revised 2019)' ? 'selected' : '' }}>Bachelor of Science in Criminology (Revised 2019)</option>
                        <option value="BSEMEng" {{ old('course')=='Bachelor of Secondary Education Major in English' ? 'selected' : '' }}>Bachelor of Secondary Education Major in English</option>
                        <option value="BSEMMath" {{ old('course')=='Bachelor of Secondary Education Major in Mathematics' ? 'selected' : '' }}>Bachelor of Secondary Education Major in Mathematics</option>
                        <option value="BSEMFil" {{ old('course')=='Bachelor of Secondary Education Major in Filipino' ? 'selected' : '' }}>Bachelor of Secondary Education Major in Filipino</option>
                        <option value="BSEMSocStud" {{ old('course')=='Bachelor of Secondary Education Major in Social Studies' ? 'selected' : '' }}>Bachelor of Secondary Education Major in Social Studies</option>
                    </select>

                <label><small>Tertiary Course </small></label>
                    <select name="tertiary_course" required>
                        <option value="">Select Tertiary Course</option>
                        <option value="BPA" {{ old('course')=='Bachelor of Public Administration' ? 'selected' : '' }}>Bachelor of Public Administration</option>
                        <option value="BSE" {{ old('course')=='Bachelor of Science in Entrepreneurship' ? 'selected' : '' }}>Bachelor of Science in Entrepreneurship</option>
                        <option value="BSCA" {{ old('course')=='Bachelor of Science in Customs Administration' ? 'selected' : '' }}>Bachelor of Science in Customs Administration</option>
                        <option value="BSHM" {{ old('course')=='Bachelor of Science in Hospitality Management' ? 'selected' : '' }}>Bachelor of Science in Hospitality Management</option>
                        <option value="BSC18" {{ old('course')=='Bachelor of Science in Criminology (Revised 2018)' ? 'selected' : '' }}>Bachelor of Science in Criminology (Revised 2018)</option>
                        <option value="BSC19" {{ old('course')=='Bachelor of Science in Criminology (Revised 2019)' ? 'selected' : '' }}>Bachelor of Science in Criminology (Revised 2019)</option>
                        <option value="BSEMEng" {{ old('course')=='Bachelor of Secondary Education Major in English' ? 'selected' : '' }}>Bachelor of Secondary Education Major in English</option>
                        <option value="BSEMMath" {{ old('course')=='Bachelor of Secondary Education Major in Mathematics' ? 'selected' : '' }}>Bachelor of Secondary Education Major in Mathematics</option>
                        <option value="BSEMFil" {{ old('course')=='Bachelor of Secondary Education Major in Filipino' ? 'selected' : '' }}>Bachelor of Secondary Education Major in Filipino</option>
                        <option value="BSEMSocStud" {{ old('course')=='Bachelor of Secondary Education Major in Social Studies' ? 'selected' : '' }}>Bachelor of Secondary Education Major in Social Studies</option>
                    </select>
                </div>

                @error('course') 
                    <p class="error">{{ $message }}</p> 
                @enderror
            </div>
    

        <div class="form-group full-width" style="margin-top:20px;">
            <label><strong>Data Privacy Notice</strong></label>
            
            <div style="border:1px solid #ccc; padding:15px; max-height:150px; overflow-y:auto; font-size:14px; background:#f9f9f9;">
                <p>
                    In accordance with the Data Privacy Act of 2012, all personal information collected 
                    through this admission form will be treated with strict confidentiality and will only 
                    be used for academic and administrative purposes.
                </p>
                <p>
                    By providing your personal information, you consent to the collection, use, and processing 
                    of your data by the institution for admission evaluation, enrollment, and related services.
                </p>
                <p>
                    Your information will not be shared with third parties without your consent unless required 
                    by law.
                </p>
            </div>

            <div style="margin-top:10px;">
                <input type="checkbox" name="privacy_agreement" id="privacy_agreement" required>
                <label for="privacy_agreement">
                    I have read and agree to the Data Privacy Notice <span style="color:red">*</span>
                </label>
            </div>
<br>
            <button type="submit">Submit</button>

            @error('privacy_agreement')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>
    </form>
    </div>
</div>
<script src="{{ asset('js/Checkbox.js') }}" ></script>
</body>
</html>