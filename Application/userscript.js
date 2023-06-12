// display selected image 
$(document).ready(function () {
    $(document).on('change', "#propertipic", (e) => {
        var src = URL.createObjectURL(e.target.files[0])
        $("#profileimg").attr("src", src);
    });
});


//display selected image on register/update user form
$(document).ready(function () {
    $(document).on('change', "#profile_pic", (e) => {
        var src = URL.createObjectURL(e.target.files[0])
        $("#profilesrc").attr("src", src);
    });
});


// change display image on click on different image on pg page
$(document).on('click', ".pgprofilesrc", function () {
    $("#pgprofilesrc").attr("src", this.src);
});


// change display image on click on different image on property
$(document).on('click', ".propertyprofilesrc", function () {
    $("#propertyprofilesrc").attr("src", this.src);
});


//validation for Adding/Updating PG's
$("#AddPg").click((e) => {
    var error = false;

    if ($("#propname").val().trim() == '') {
        e.preventDefault();
        error = true;
        $("#Errpropname").html("<p class='text-danger'>Please enter your name..</p>")
    } else {
        $("#Errpropname").html("<p class='text-success'>Is correct...</p>");
    }
    if ($("#shortdesc").val().trim() == '') {
        e.preventDefault();
        error = true;
        $("#Errshortdesc").html("<p class='text-danger'>Please enter some short discription..</p>")
    } else {
        $("#Errshortdesc").html("<p class='text-success'>Is correct...</p>");
    }
    if ($("#longdesc").val().trim() == '') {
        e.preventDefault();
        error = true;
        $("#Errlongdesc").html("<p class='text-danger'>Please enter some discription..</p>")
    } else {
        $("#Errlongdesc").html("<p class='text-success'>Is correct...</p>");
    }
    if ($("#country").val() == 'null') {
        e.preventDefault();
        error = true;
        $("#Errcountry").html("<p class='text-danger'>Please select country..</p>")
    } else {
        $("#Errcountry").html("<p class='text-success'>Is correct...</p>");
    }
    if ($("#state").val() == 'null') {
        e.preventDefault();
        error = true;
        $("#Errstate").html("<p class='text-danger'>Please select state..</p>")
    } else {
        $("#Errstate").html("<p class='text-success'>Is correct...</p>");
    }
    if ($("#city").val() == 'null') {
        e.preventDefault();
        error = true;
        $("#Errcity").html("<p class='text-danger'>Please select city..</p>")
    } else {
        $("#Errcity").html("<p class='text-success'>Is correct...</p>");
    }
    if ($("#Address").val().trim() == '') {
        e.preventDefault();
        error = true;
        $("#Erraddress").html("<p class='text-danger'>Please enter your street Address..</p>")
    } else {
        $("#Erraddress").html("<p class='text-success'>Is correct...</p>");
    }
    if ($("#Zip").val().trim() == '' || $("#Zip").val().trim().length != 6) {
        e.preventDefault();
        error = true;
        $("#Errzip").html("<p class='text-danger'>Please enter 6 digit postcode..</p>")
    } else {
        $("#Errzip").html("<p class='text-success'>Is correct...</p>");
    }
    if ($("#room").val() == 'null') {
        e.preventDefault();
        error = true;
        $("#Errrooms").html("<p class='text-danger'>Please enter your rooms..</p>")
    } else {
        $("#Errrooms").html("<p class='text-success'>Is correct...</p>");
    }


    if ($("#price").val().trim() == "") {
        e.preventDefault();
        error = true;
        $("#Errprice").html("<p class='text-danger'>Please enter your price..</p>")
    } else {
        $("#Errprice").html("<p class='text-success'>Is correct...</p>");
    }

    if ($("#pggender").val() == 'null') {
        e.preventDefault();
        error = true;
        $("#Errpggender").html("<p class='text-danger'>Please select Gender preference..</p>")
    } else {
        $("#Errpggender").html("<p class='text-success'>Is correct...</p>");
    }
    if ($("#type").val() == 'null') {
        e.preventDefault();
        error = true;
        $("#Errtype").html("<p class='text-danger'>Please your type..</p>")
    } else {
        $("#Errtype").html("<p class='text-success'>Is correct...</p>");
    }
    if ($("#status").val() == 'null') {
        e.preventDefault();
        error = true;
        $("#Errstatus").html("<p class='text-danger'>Please enter your status..</p>")
    } else {
        $("#Errstatus").html("<p class='text-success'>Is correct...</p>");
    }
    if (error) {
        return false;
    } else {
        return true;
    }
});


//validation for Adding/Updating Properties
$("#AddProp").click((e) => {
    var error = false;

    if ($("#propname").val().trim() == '') {
        e.preventDefault();
        error = true;
        $("#Errpropname").html("<p class='text-danger'>Please enter your name..</p>")
    } else {
        $("#Errpropname").html("<p class='text-success'>Is correct...</p>");
    }
    if ($("#shortdesc").val().trim() == '') {
        e.preventDefault();
        error = true;
        $("#Errshortdesc").html("<p class='text-danger'>Please enter some short discription..</p>")
    } else {
        $("#Errshortdesc").html("<p class='text-success'>Is correct...</p>");
    }
    if ($("#longdesc").val().trim() == '') {
        e.preventDefault();
        error = true;
        $("#Errlongdesc").html("<p class='text-danger'>Please enter some discription..</p>")
    } else {
        $("#Errlongdesc").html("<p class='text-success'>Is correct...</p>");
    }
    if ($("#country").val() == 'null') {
        e.preventDefault();
        error = true;
        $("#Errcountry").html("<p class='text-danger'>Please select country..</p>")
    } else {
        $("#Errcountry").html("<p class='text-success'>Is correct...</p>");
    }
    if ($("#state").val() == 'null') {
        e.preventDefault();
        error = true;
        $("#Errstate").html("<p class='text-danger'>Please select state..</p>")
    } else {
        $("#Errstate").html("<p class='text-success'>Is correct...</p>");
    }
    if ($("#city").val() == 'null') {
        e.preventDefault();
        error = true;
        $("#Errcity").html("<p class='text-danger'>Please select city..</p>")
    } else {
        $("#Errcity").html("<p class='text-success'>Is correct...</p>");
    }
    if ($("#Address").val().trim() == '') {
        e.preventDefault();
        error = true;
        $("#Erraddress").html("<p class='text-danger'>Please enter your street Address..</p>")
    } else {
        $("#Erraddress").html("<p class='text-success'>Is correct...</p>");
    }
    if ($("#Zip").val().trim() == '') {
        e.preventDefault();
        error = true;
        $("#Errzip").html("<p class='text-danger'>Please enter your postcode..</p>")
    } else {
        $("#Errzip").html("<p class='text-success'>Is correct...</p>");
    }
    if ($("#room").val() == 'null') {
        e.preventDefault();
        error = true;
        $("#Errrooms").html("<p class='text-danger'>Please enter your rooms..</p>")
    } else {
        $("#Errrooms").html("<p class='text-success'>Is correct...</p>");
    }
    if ($("#area").val().trim() == '') {
        e.preventDefault();
        error = true;
        $("#Errarea").html("<p class='text-danger'>Please enter your carpet Area ..</p>")
    } else {
        $("#Errarea").html("<p class='text-success'>Is correct...</p>");
    }

    if ($("#price").val().trim() == "") {
        e.preventDefault();
        error = true;
        $("#Errprice").html("<p class='text-danger'>Please enter your price..</p>")
    } else {
        $("#Errprice").html("<p class='text-success'>Is correct...</p>");
    }

    if ($("#catagory").val() == 'null') {
        e.preventDefault();
        error = true;
        $("#Errcat").html("<p class='text-danger'>Please select catagory..</p>")
    } else {
        $("#Errcat").html("<p class='text-success'>Is correct...</p>");
    }
    if ($("#type").val() == 'null') {
        e.preventDefault();
        error = true;
        $("#Errtype").html("<p class='text-danger'>Please your type..</p>")
    } else {
        $("#Errtype").html("<p class='text-success'>Is correct...</p>");
    }
    if ($("#status").val() == 'null') {
        e.preventDefault();
        error = true;
        $("#Errstatus").html("<p class='text-danger'>Please enter your status..</p>")
    } else {
        $("#Errstatus").html("<p class='text-success'>Is correct...</p>");
    }

    if ($("#type").val() == 'pg/co-living') {
        if ($("#pggender").val() == "null") {
            $("#Errpggender").html("<p class='text-danger'>Please enter your gender preference..</p>")
        } else {
            $("#Errpggender").html("<p class='text-success'>Is correct...</p>");
        }
    }
    if (error) {
        return false;
    }
});


//Validation for update/register new User
$("#Regsubmit").click((e) => {
    var error = false;

    if ($("#type").val() == "null") {
        e.preventDefault();
        error = true;
        $("#typeErr").html("<p class='text-danger'>Please select Who you are..</p>");
    } else {
        $("#typeErr").html("<p class='text-success'>*</p>");
    }

    if ($("#name").val().trim() == '') {
        e.preventDefault();
        error = true;
        $("#nameErr").html("<p class='text-danger'>Please enter your name..</p>")
    } else {
        $("#nameErr").html("<p class='text-success'>*.</p>");
    }

    var patten = /^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$/;
    if ($("#Regemail").val().trim() == "" || !patten.test($("#Regemail").val()) || $("#Regemail").val().trim() == '') {
        e.preventDefault();
        error = true;
        $("#RegemailErr").html("<p class='text-danger'>Please enter valid email..</p>");
    } else {
        $("#RegemailErr").html("<p class='text-success'>*</p>");
    }

    if ($("#Regpassword").val().trim() == '') {
        e.preventDefault();
        error = true;
        $("#RegpasswordErr").html("<p class='text-danger'>Please enter your email..</p>");
    } else {
        $("#RegpasswordErr").html("<p class='text-success'>*</p>");
    }

    if ($("#female").is(":checked") || $("#male").is(":checked")) {
        $("#genderErr").html("<p class='text-success'>*</p>");
    } else {
        e.preventDefault();
        error = true;
        $("#genderErr").html("<p class='text-danger'>Please select your gender..</p>");
    }

    var contact = $("#contact").val();
    if ($("#contact").val().trim() == "" || !contact.match(/^\d{10}$/)) {
        e.preventDefault();
        error = true;
        $("#contactErr").html("<p class='text-danger'>Please enter a proper contact no...</p>");
    } else {
        $("#contactErr").html("<p class='text-success'>*</p>");
    }
    if ($("#country").val() == "null") {
        e.preventDefault();
        error = true;
        $("#countryErr").html("<p class='text-danger'>Please select your country..</p>");
    } else {
        $("#countryErr").html("<p class='text-success'>*</p>");
    }
    if ($("#state").val() == "null") {
        e.preventDefault();
        error = true;
        $("#stateErr").html("<p class='text-danger'>Please select your state..</p>");
    } else {
        $("#stateErr").html("<p class='text-success'>*</p>");
    }
    if ($("#city").val() == "null") {
        e.preventDefault();
        error = true;
        $("#cityErr").html("<p class='text-danger'>Please select your city..</p>");
    } else {
        $("#cityErr").html("<p class='text-success'>*</p>");
    }

    if ($("#str_address").val().trim() == "") {
        e.preventDefault();
        error = true;
        $("#ErrAdd").html("<p class='text-danger'>Please enter your clocal Address..</p>");
    } else {
        $("#ErrAdd").html("<p class='text-success'>*</p>");
    }
    if ($("#Zip").val().trim() == "" || $("#Zip").val().length != 6) {
        e.preventDefault();
        error = true;
        $("#Errzip").html("<p class='text-danger'>Please enter 6 digit Postcode..</p>");
    } else {
        $("#Errzip").html("<p class='text-success'>*</p>");
    }

    if (error) {
        return false;
    }

});


//For Sending OTP on login 
$("#sendotponlogin").click((e) => {
    var error = false;
    var email = $("#otpemail").val();
    var emaileheck = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if (emaileheck.test($("#otpemail").val()) || $("#otpemail").val().trim() == '') {
        $("#otpemailErr").html("<p class='text-success'></p>");
        var email = $("#otpemail").val();
    } else {
        error = true;
        $("#otpemailErr").html("<p class='text-danger'>Pls check the email...</p>");
        e.preventDefault();
    }
    if (!error) {
        $.ajax({
            type: 'POST',
            data: {
                'action': "sendFpOtp",
                'email': email,
            },
            success: function (response) {
                if (response == 'success') {
                    $("#showOtpMsg").html("<div class='alert alert-info'><strong>Otp Send on Register Mobile number & Email</strong></div>");
                } else {
                    $("#showOtpMsg").html("<div class='alert alert-info'><strong>Invalid email ..</strong></div>");
                }
            }
        });
    }
});


//login when Forget Password
$("#loginthroughFP").click((e) => {
    var error = false;
    var emaileheck = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if (!emaileheck.test($("#otpemail").val())) {
        $("#otpemailErr").html("<p class='text-danger'>Pls check the email...</p>");
        e.preventDefault();
        error = true;

    } else {
        $("#otpemailErr").html("<p class='text-danger'></p>");
    }

    if ($('#FPotp').val() == '') {
        $("#otpErr").html("<p class='text-danger'>Pls check your OTP...</p>");
        e.preventDefault();
        error = true;
    } else {
        $("#otpErr").html("<p class='text-danger'></p>");
    }
    if (error) {
        return false;
    } else {
        var FPotp = $("#FPotp").val();
        var email = $("#otpemail").val();

        $.ajax({
            type: 'POST',
            data: {
                'action': "checkFpOtp",
                'otp': FPotp,
                'email': email,
            },
            success: function (response) {
                if (response == 'success') {
                    $("#forgetpasswordmodal").modal('hide');
                    $("#resetpassword").modal('show');
                } else {
                    $("#showOtpMsg").html("<div class='alert alert-danger px-3 '><strong>OTP doesn't matched..</strong></div>");
                }
            }

        });
    }
});


//Ajax for Forget Password
$("#changePassword").click((e) => {
    var error = false;

    if ($("#newPassword").val().trim() == '') {
        $("#passErr").html("<p class='text-danger'>Pls enter minimum 6 digit password...</p>");
        e.preventDefault();
        error = true;
    } else {
        $("#passErr").html("<p class='text-success'></p>");
    }

    if ($("#conPassword").val().trim() == '') {
        $("#conPasswordErr").html("<p class='text-danger'>pls confrim password.. </p>");
        e.preventDefault();
        error = true;
    } else {
        $("#conPasswordErr").html("<p class='text-success'></p>");
    }

    if (!error) {

        var newPass = $("#newPassword").val();
        var confirmPass = $("#conPassword").val();

        if (newPass != confirmPass) {
            $("#wrongPassword").html("<div class='alert alert-danger px-3 '><strong>Password doesn't matched..</strong></div>");
        } else {
            $("#wrongPassword").html("");
            $.ajax({
                type: 'POST',
                data: {
                    'action': "changepassword",
                    'password': newPass,
                },
                success: function (response) {
                    if (response == 'success') {
                        $("#resetpassword").modal('hide');
                        toastr.success('Whoo..! Password Has been Updated...');
                        //    alert("Whoo..! Password Has been Updated...");
                        $("#userlogin").modal('show');
                    }
                }

            });
        }
    } else {
        return false;
    }
});


// Ajax call for LogOut
$("#logout").click(() => {
    if (confirm('Are you sure to logout')) {
        $.ajax({
            type: 'POST',
            data: {
                'action': "logout",
            },
            success: function (response) {
                if (response == 'success') {
                    $("#login").show();
                    $("#logout").addClass('d-none');
                    $("#signin").show();
                    $("#userloginbtn").show();
                    location.href = "index.php";
                } else {
                }
            }
        });
    }
});


// Function for Selecting state on select of country
function selectstate() {
    var country = $('#country').val();
    $.ajax({
        type: 'POST',
        data: {
            'action': "selectstate",
            'country': country,
        },
        success: function (response) {
            var response = JSON.parse(response);
            $('#state').html('<option selected disabled value="">Choose...</option>');
            response.forEach(element => {
                $('#state').append(`<option value="${element.state_id}">${element.state_name}</option>`);
            });
        }
    });
}


// function for selecting city on basis of selected city
function selectcity() {
    var state = $('#state').val();
    $.ajax({
        type: 'POST',
        data: {
            'action': "selectcity",
            'state': state,
        },
        success: function (response) {
            response = JSON.parse(response);
            $('#city').html('<option selected disabled value="">Choose...</option>' + response);
            response.forEach(element => {
                $("#city").append(`<option value="${element.city_id}">${element.city_name}</option>`);
            });
        }
    });
}


// normal user login call
$("#userloginbtn").click((e) => {
    var error = true;
    if ($("#email").val() == "") {
        $("#emailErr").html("<p class='text-danger'>Please enter your Email..</p>")
        e.preventDefault();
        error = false;
    } else {

    }
    if ($("#password").val() == "") {
        $("#passwordErr").html("<p class='text-danger'>Please enter your password..</p>")
        e.preventDefault();
        error = false;
    }

    if (!error) {
        return false;
    } else {
        var email = $("#email").val();
        var password = $("#password").val();
        if ($("#rememberme").is(":checked")) {
            var rememberme = 'on';
        } else {
            var rememberme = 'off';
        }
        $.ajax({
            type: 'POST',
            data: {
                'action': "userlogin",
                'email': email,
                'password': password,
                'rememberme': rememberme,
            },
            success: function (response) {
                if (response == 'success') {
                    setTimeout(location.reload(), 5000);
                  
                } else {
                    $("#showError").html("<div class='alert alert-danger'><strong>Error !</strong>Wrong Email or Password </div>")
                }
            }
        });
    }
});


// Ajax for owner details for pgs 
$("#pgcontact_owner").click(() => {
    var id = $("#pgcontact_owner").val();
    $.ajax({
        type: 'POST',
        data: {
            'action': "pgcontact_detail",
            'propid': id,
        },
        success: function (response) {

            if (response != 'null') {
                response = JSON.parse(response);
                $("#ownerdetails").html(`
                <div class='fs-4'>
                <div class='d-flex justify-content-center' >
                    <img class=' img-fluid border border-dark border-3 p-1 rounded' src='https://propertyimgbucket1.s3.ap-south-1.amazonaws.com/images/images/user${response.user_id}/${response.profile_pic}' width='150px' height='150px' alt=''>
                </div>
                <div class='mt-3' id='ownerdetails'>
                    <strong> Pg id  : </strong>${response.pg_id}<span> </span> <br>
                    <strong>  Name  : </strong> <span> ${response.name}</span> <br> 
                    <strong> E-mail info : </strong> <span> ${response.email}</span> <br> 
                    <strong> Contact info  : </strong> <span> ${response.contact_no}</span> <br> 
                </div>
                </div>  
                `);
            } else {
                $("#ownerdetails").html(`                
                <div class='fs-4'>
                    <div class='mt-3' id='ownerdetails'>
                        <div class='text-danger d-flex justify-content-center' >
                            Property has no owner...
                        </div>
                    </div>
                </div>
                `);
            }
        }
    });
});

// Ajax for owner details for properties
$("#propertycontact_owner").click(() => {
    var id = $("#propertycontact_owner").val();
    $.ajax({
        type: 'POST',
        data: {
            'action': "porpertycontact_detail",
            'propid': id,
        },
        success: function (response) {
            if (response != "null") {
                response = JSON.parse(response);
                $("#ownerdetails").html(`
            <div class='fs-4'>
              <div class='d-flex justify-content-center' >
                <img class=' img-fluid border border-dark border-3 p-1 rounded ' src='https://propertyimgbucket1.s3.ap-south-1.amazonaws.com/images/images/user${response.user_id}/${response.profile_pic}' width='150px' height='150px' alt=''>
              </div>
              <div class='mt-3' id='ownerdetails'>
                  <strong> property id  : </strong>${response.property_id}<span> </span> <br>
                  <strong>  Name  : </strong> <span> ${response.name}</span> <br> 
                  <strong> E-mail info : </strong> <span> ${response.email}</span> <br> 
                  <strong> Contact info  : </strong> <span> ${response.contact_no}</span> <br> 
              </div>
              </div>`);
            } else {
                $("#ownerdetails").html(`
                <div class='fs-4'>
                  <div class='mt-3' id='ownerdetails'>
                    <div class='text-danger d-flex justify-content-center' >
                    Property has no owner...
                    </div>
                  </div>
                </div>
                `);
            }
        }
    });
});

// deleting any property
$(document).on("click", ".del", function () {
    var delid = (this.id).slice(3);
    var msg = "Are you sure you want to delete this property..?";
    if (confirm(msg) == true) {
        $.ajax({
            type: 'POST',
            data: {
                'action': "delProp",
                'delid': delid,
            },
            success: function (response) {
                location.reload();
            }
        });
    } else {
        return false;
    }
});


//deleting any Pg
$(document).on("click", ".delpg", function () {
    var delid = (this.id).slice(5);
    var msg = "Are you sure you want to delete this property..?";
    if (confirm(msg) == true) {
        $.ajax({
            type: 'POST',
            data: {
                'action': "delPg",
                'delid': delid,
            },
            success: function (response) {
                location.reload();
            }
        });
    } else {
        return false;
    }
});


//Rating ajax call for pg section.
$("#pgrating").change(() => {
    var rating = $("#pgrating").val();

    $.ajax({
        type: 'POST',
        data: {
            'action': 'pgratings',
            'rating': rating,
        },
        success: function (response) {
        toastr.success("Thank's For Rating..."); 
        }
    });
});

//Rating ajax call fo property section..
$("#propertyrating").change(() => {
    var rating = $("#propertyrating").val();

    $.ajax({
        type: 'POST',
        data: {
            'action': 'propertyratings',
            'rating': rating,
        },
        success: function (response) {
            alert("Thanks..for rating..!!")
        }
    });
});


//Register new user
$("#usersigninbtn").click((e) => {
    var error2 = false;
    if ($("#otp").val() == "" || $("#otp").val().length != 6) {
        e.preventDefault();
        error2 = true;
        console.log("wrong");
        $("#otpverifyErr").html("<p class='text-danger'>Please enter 6 digit otp..</p>");
    } else {
        $("#otpverifyErr").html("<p class='text-success'></p>");
    }

    if (!error2) {
        var otp = $("#otp").val();
        $.ajax({
            type: 'POST',
            data: {
                'action': "verifyotp",
                'otp': otp,
            },
            success: function (response) {
                if (response == "success") {
                    location.href = "index.php";
                } else {
                    $("#showErrorinSendotp").html("<div class='alert alert-danger' role='alert'>Wrong OTP ..!</div>")
                }
            }
        });
    }
});


// Resend OTP in Register user
$("#resendotp").click(() => {
    $.ajax({
        type: 'POST',
        data: {
            'action': "resendotp",
        },
        success: function (response) {
            if (response == 'Done') {
                $("#showErrorinSendotp").html("<div class='alert alert-success' role='alert'>OTP send..!</div>")
                // toastr.success('OTP send..!');
            }
        }
    });
});

//searching for pg 
function searchpg() {
    var pageid = parseInt(id);
    var rooms = $("#rooms").val();
    var NumRooms = $("#NumRooms").val();
    var location = $("#location").val();
    var preference = $("#preference").val();
    var searchele = $("#searchele").val();
    var price = $("#Price").val();
    $.ajax({
        type: 'POST',
        data: {
            'action': 'showlistforPgs',
            'rooms': rooms,
            'NumRooms': NumRooms,
            'location': location,
            'preference': preference,
            'price': price,
            'searchele': searchele,
            'pageid': pageid,
        },
        success: function (response) {
            if (response != "") {
                var response = JSON.parse(response);
                $('#showlists').html('');
                if (isNaN(pageid)) {
                    pageid = 1
                }
                var results_per_page = 2;
                var page_first_result = (pageid - 1) * results_per_page;
                var number_of_result = response.length;
                var number_of_page = Math.ceil(number_of_result / results_per_page);
                var data = response.slice(parseInt(page_first_result), parseInt(page_first_result + results_per_page));
                data.forEach(element => {
                    var photo = element.Photos.split(",");
                    $('#showlists').append(`
          <div class='row mt-5 py-3 gx-0' style='padding-inline: 300px;'>
            <div class='col-6 p-2 d-flex justify-content-center' >
              <img class='img-fluid p-2 border border-3 border-dark rounded' width='500px' height='500px'  src='https://propertyimgbucket1.s3.ap-south-1.amazonaws.com/images/Pgimages/pg${element.pg_id}/${photo[0]}' alt='pg_img'>
            </div>
            <div class=' col-6 py-3'>
              <div class='fs-3 '>${element.pg_name.charAt(0).toUpperCase() + element.pg_name.slice(1)}</div><br> 
              <p class='text-muted' >${element.sort_descreption}</p>
              <p class='text-muted' ><img src='https://www.svgrepo.com/show/513317/location-pin.svg' width='20px' height='20px' >${element.country},${element.state},${element.city}</p>
              <p><span class='text-danger fs-4'>Prices start at </span></p>
              <p class='ms-3 fs-3 '> ${element.price}/Month</p> 
              <button class='pginfo btn btn-danger' id='info${element.pg_id}'><strong>  More info </strong> </button> 
            </div>
            </div>
          <hr>
          `);
                });
                var x;
                if (pageid == NaN || pageid == 1) {
                    x = 1;
                } else {
                    x = pageid - 1;
                }
                $('#showlists').append(`
          <div class='d-flex justify-content-center'>
           <ul class='pagination mt-3' id = 'pagelist'>
           <li class='page-item'><a class='pgpagelink page-link' href='#' id = 'link${x}'>Previous</a></li>`);
                for (var pages = 1; pages <= number_of_page; pages++) {
                    $('#pagelist').append(`<li class='page-item'><a class='pgpagelink page-link' id = 'link${pages}' href='#' > ${pages} </a></li>`);
                }
                var y;
                if (pageid == number_of_page) {
                    y = parseInt(pageid);
                } else {
                    y = parseInt(pageid) + 1;
                }
                $('#pagelist').append(`<li class='page-item'><a class='pgpagelink page-link' href='#' id = 'link${y}' >Next</a></li>`);
                $('#pagelist').append(`
           </ul>
         </div>`);
                if (pageid > number_of_page) {
                    $("#link1").trigger('click');
                }
                if ($("#link" + pageid).text() != "Previous") {
                    $("#link" + pageid).addClass('text-danger');
                }
            } else {
                $('#showlists').html("<div class='ms-2 text-danger text-center fs-3'><p class='text-center'>Sorry, No Pg found :( </p></div>");
            }
        }
    });
}

//pagination while searching element in PG
$(document).on('click', '.pgpagelink', function() {
    id = (this.id).slice(4);
    searchpg();
  });

//searching for property
function searchprop() {
    var rooms = $("#rooms").val();
    var type = $("#type").val();
    var location = $("#location").val();
    var catagory = $("#catagory").val();
    var searchele = $("#searchele").val();
    var price = $("#Price").val();

    $.ajax({
        type: 'POST',
        data: {
            'action': 'showlistforproperty',
            'rooms': rooms,
            'type': type,
            'location': location,
            'catagory': catagory,
            'searchele': searchele,
            'price': price,
            'pageid': id,
        },
        success: function (response) {
            if (response == "") {
                $('#showlists').html("<div class='ms-2 text-danger text-center fs-3'><p class='text-center'>Sorry, No Pg found :( </p></div>");
            } else {
                var response = JSON.parse(response);
                var pageid = id;
                $('#showlists').html("");
                if (isNaN(pageid)) {
                    pageid = 1
                }
                var results_per_page = 3;
                var page_first_result = (pageid - 1) * results_per_page;
                var number_of_result = response.length;
                var number_of_page = Math.ceil(number_of_result / results_per_page);
                var data = response.slice(parseInt(page_first_result), parseInt(page_first_result + results_per_page));
                data.forEach(element => {
                    var photo = element.Photos.split(",");
                    var tag;
                    if (element.type == 'Rent') {
                        tag = "/Month";
                    } else {
                        tag = ' Only';
                    };
                    $('#showlists').append(`
      <div class='row mt-3  py-3 gx-0' style='padding-inline: 300px;'>
      <div class='col-6 p-2 d-flex justify-content-center' >
      <img class=' p-2 border border-3 border-dark rounded' width='500px' height='400px'  src='https://propertyimgbucket1.s3.ap-south-1.amazonaws.com/images/propertyImg/property${element.property_id}/${photo[0]}' alt='property img'>
      </div>
      <div class=' col-6 py-3'>
      <div class='display-5'>${element.property_name.charAt(0).toUpperCase() + element.property_name.slice(1)}</div><br> 
      <p class='text-muted' >${element.sort_descreption}</p>
      <p class='text-muted' ><img src='https://www.svgrepo.com/show/513317/location-pin.svg' width='20px' height='20px' > ${element.country} , ${element.state} , ${element.city}</p>
      <p class='fs-5'>Listed for : <span>${element.type}</span></p>
      <p><span class='text-danger fs-4'>Prices start at </span></p>
      <p class='ms-3 fs-3 '>${element.price} ${tag}</p> 
      <button class='propertyinfo btn btn-danger' id='info${element.property_id}'><strong>  More info </strong>  </button> 
    </div>
    </div>
    <hr>
    ";
        `);
                });
                var x;
                if (pageid == NaN || pageid == 1) {
                    x = 1;
                } else {
                    x = pageid - 1;
                }
                $('#showlists').append(`
          <div class='d-flex justify-content-center'>
           <ul class='pagination mt-3' id = 'pagelist'>
           <li class='page-item'><a class='propertypagelink page-link' href='#' id = 'link${x}'>Previous</a></li>`);
                for (var pages = 1; pages <= number_of_page; pages++) {
                    $('#pagelist').append(`<li class='page-item'><a class='propertypagelink page-link' id = 'link${pages}' href='#' > ${pages} </a></li>`);
                }
                var y;
                if (pageid == number_of_page) {
                    y = parseInt(pageid);
                } else {
                    y = parseInt(pageid) + 1;
                }
                $('#pagelist').append(`<li class='page-item'><a class='propertypagelink page-link' href='#' id = 'link${y}' >Next</a></li>`);
                $('#pagelist').append(`
           </ul>
         </div>`);
                if (pageid > number_of_page) {
                    $("#link1").trigger('click');
                }

                if ($("#link" + pageid).text() != "Previous") {
                    $("#link" + pageid).addClass('text-danger');
                }
            }
        }
    });
}


//pagination while searching element in Property
$(document).on('click', '.propertypagelink', function() {
    id = (this.id).slice(4);
    searchprop();
  });

//change event on click of different image in updatepg
$(document).on('click', ".updatepgprofilesrc", function () {
    $("#updatepgimg").attr("src", this.src);
}); 

//change event on click of different image in updateproperty
$(document).on('click', ".updateprofileprofilesrc", function () {
    $("#updateprofileprofileimg").attr("src", this.src);
}); 