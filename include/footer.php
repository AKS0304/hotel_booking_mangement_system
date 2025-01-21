<div class="container-fluid mt-5">
    <div class="row">
        <div class="col-lg-4 p-4">
            <h3 class="h-font fw-bold fs-3 mb-2"><?php echo $settings_r['site_title'] ?></h3>
            <p class="about-us-text" id="aboutUsText">
            <?php echo $settings_r['site_about'] ?>
            </p>
        </div>
        <div class="col-lg-4 p-4">
            <h5 class="mb-3">Links</h5>
            <a href="index.php" class="d-inline-block mb-2 text-decoration-none">Home</a><br>
            <a href="rooms.php" class="d-inline-block mb-2 text-decoration-none">Rooms</a><br>
            <a href="facilities.php" class="d-inline-block mb-2 text-decoration-none">Facilities</a><br>
            <a href="contact.php" class="d-inline-block mb-2 text-decoration-none">Contact us</a><br>
            <a href="about.php" class="d-inline-block mb-2 text-decoration-none">About us</a>
        </div> 
        <div class="col-lg-4 p-4">
            <h5 class="mb-3">Follow us</h5>
            <?php
               if($contact_r['tw']!=''){
                echo<<<data
                    <a href="$contact_r[tw]" class="d-inline-block text-dark text-decoration-none mb-2">
                      <i class="bi bi-twitter me-1">Twitter</i>
                    </a> <br>
                data;
               }
            ?>   
            
            <a href="<?php echo $contact_r['fb'] ?>" class="d-inline-block text-dark text-decoration-none mb-2">
                <i class="bi bi-facebook me-1">Facebook</i>
            </a> <br>
            <a href="<?php echo $contact_r['insta'] ?>" class="d-inline-block text-dark text-decoration-none">
                <i class="bi bi-instagram me-1">Instagram</i>
            </a><br>
        </div>  
    </div>
</div>

<h6 class="text-center bg-dark text-white p-3 m-0">Design & Developed by AKS</h6>
<h6 class="text-center bg-dark text-white p-3 m-0">© Copyright 2025. ALL RIGHTS RESERVED</h6>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity=
"sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

<script>


    window.addEventListener('DOMContentLoaded', (event) => {
        const aboutUsText = document.getElementById('aboutUsText');
        const scrollSpeed = 50; // Adjust the scroll speed as needed

        function autoScroll() {
            aboutUsText.scrollTop += 1; // Scroll down by 1 pixel
            if (aboutUsText.scrollTop >= aboutUsText.scrollHeight - aboutUsText.clientHeight) {
                // Reset to the top if the bottom is reached
                aboutUsText.scrollTop = 0;
            }
        }

        setInterval(autoScroll, scrollSpeed);
    });
    

    function alert(type,msg,position='body'){
        let bs_class = (type == 'success') ? 'alert-success' : 'alert-danger';
        let element = document.createElement('div');
        element.innerHTML = `
            <div class="alert ${bs_class} alert-dismissible fade show" role="alert">
                <strong class="me-3">${msg}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;

        if(position == 'body'){
        document.body.append(element);
        element.classList.add('custom-alert');
        }
        else{
            document.getElementById(position).appendChild(element);
        }
        setTimeout(remAlert, 2000);
    }

    function remAlert(){
        document.getElementsByClassName('alert')[0].remove();
    }

    function setActive(){
        let navbar = document.getElementById('nav-bar');
        let a_tags = navbar.getElementsByTagName('a');

        for(i=0;i<a_tags.length;i++){
            let file = a_tags[i].href.split('/').pop();
            let file_name = file.split('.')[0];

            if(document.location.href.indexOf(file_name)>= 0){
                a_tags[i].classList.add('active');
            }
        }
    }

    let register_form = document.getElementById('register-form');

    register_form.addEventListener('submit', function(e){
       e.preventDefault();

       let data = new FormData();

       data.append('name',register_form.elements['name'].value);
       data.append('email',register_form.elements['email'].value);
       data.append('phonenum',register_form.elements['phonenum'].value);
       data.append('address',register_form.elements['address'].value);
       data.append('pincode',register_form.elements['pincode'].value);
       data.append('dob',register_form.elements['dob'].value);
       data.append('pass',register_form.elements['pass'].value);
       data.append('cpass',register_form.elements['cpass'].value);
       data.append('profile',register_form.elements['profile'].files[0]);
       data.append('register','');

       var myModal = document.getElementById('registerModal');
       var modal = bootstrap.Modal.getInstance(myModal);
       modal.hide();

        let xhr = new XMLHttpRequest();
        xhr.open("POST","ajax/login_register.php",true);

        xhr.onload = function()
        {
           if(this.responseText == 'pass_mismatch'){
              alert('error',"Password Mismatch!");
           }
           else if(this.responseText == 'email_already'){
              alert('error',"Email is already registered!");
           }
           else if(this.responseText == 'phone_already'){
              alert('error',"Phone number is already registered!");
           }
           else if(this.responseText == 'inv_img'){
              alert('error',"Only JPG, WEBP, PNG images are allowed!");
           }
           else if(this.responseText == 'upd_failed'){
              alert('error',"Image upload failed!");
           }
           else if(this.responseText == 'mail_failed'){
              alert('error',"Cannot send confirmation email!");
           }
           else if(this.responseText == 'ins_failed'){
              alert('error',"Registration failed! Server down");
           }
           else{
            alert('success',"Registration successful. You can now login!");
            register_form.reset();
           }
        } 

        xhr.send(data);

    });


    let login_form = document.getElementById('login-form');
    
    login_form.addEventListener('submit', function(e){
       e.preventDefault();

       let data = new FormData();

       data.append('email_mob',login_form.elements['email_mob'].value);
       data.append('pass',login_form.elements['pass'].value);
       data.append('login','');

       var myModal = document.getElementById('loginModal');
       var modal = bootstrap.Modal.getInstance(myModal);
       modal.hide();

        let xhr = new XMLHttpRequest();
        xhr.open("POST","ajax/login_register.php",true);

        xhr.onload = function()
        {

           if(this.responseText == 'inv_email_mob'){
              alert('error',"Invalid Email or Mobile Number!");
           }
           else if(this.responseText == 'inactive'){
              alert('error',"Account Suspended! Please contact Admin.");
           }
           else if(this.responseText == 'invalid_pass'){
              alert('error',"Invalid Password!");
           }
          else{
                let fileurl = window.location.href.split('/').pop().split('?').shift();
                if(fileurl == 'room_details.php'){
                    window.location = window.location.href;
                }
                else{
                window.location = window.location.pathname;
                }
           }
        } 
        xhr.send(data);
    });
    

    let forget_form = document.getElementById('forget-form');

    forget_form.addEventListener('submit', function(e){
        e.preventDefault();

        let data = new FormData();

        data.append('phonenum', forget_form.elements['phonenum'].value);
        data.append('email', forget_form.elements['email'].value);
        data.append('dob', forget_form.elements['dob'].value);
        data.append('forget', '');

        var myModal = document.getElementById('forgetModal');
        var modal = bootstrap.Modal.getInstance(myModal);
        modal.hide();

        var xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/login_register.php", true); 

        xhr.onload = function() {

            var response = this.responseText.trim(); // Trim the response
            
            if (response == 'inv_cred') {
                alert('error','Invalid Credentials! Please enter the correct details.');
            }
            else if (response == 'success') {
                alert('success','Validation is confirmed!');
                // Open the passResetModal modal
                var passResetModal = new bootstrap.Modal(document.getElementById('passResetModal'));
                passResetModal.show();
                forget_form.reset();
            } 
        };

        xhr.send(data);
    });

    
    let pass_reset_form = document.getElementById('passReset-form');

    pass_reset_form.addEventListener('submit', function(e) {
        e.preventDefault();

        let new_pass = pass_reset_form.elements['new_pass'].value;
        let confirm_pass = pass_reset_form.elements['confirm_pass'].value;

        if (new_pass != confirm_pass) {
            alert('error','Password do not match!');
            return false;
        }

        let data = new FormData();

        data.append('reset', '');
        data.append('new_pass', new_pass);
        data.append('confirm_pass', confirm_pass);

        var myModal = document.getElementById('passResetModal');
        var modal = bootstrap.Modal.getInstance(myModal);
        modal.hide();

        var xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/login_register.php", true); 

        xhr.onload = function() {
            if (this.responseText == 'mismatch') {
                alert('error','Password do not match!');
            } 
            else if (this.responseText == 'error') {
                alert('error','Updation failed!');
            } 
            else {
                alert('success','Changes saved!');
                pass_reset_form.reset();
            }
        };

        xhr.send(data);
    });


    function checkLoginToBook(status,room_id){
        if(status){
            window.location.href='confirm_booking.php?id='+room_id;
        }
        else{
            alert('error','Please login to book room!');
        }
    }


    setActive();
</script>