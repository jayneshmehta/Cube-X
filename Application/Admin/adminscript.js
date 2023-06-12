//login to Admin
$("#Login").click(() => {
    var username = $('#username').val();
    var password = $('#password').val();
    // var Emailpattern = /[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$/ ;
    if (username == "" || password == "") {
        event.preventDefault()
        event.stopPropagation()
        alert("Please Enter a valid email or password");
    } else {

        if ($('[type="checkbox"]').is(":checked")) {
            var rememberMe = 'on';
        } else {
            var rememberMe = 'off';
        }

        $.ajax({
            type: 'POST',
            data: {
                'action': "adminlogin",
                'username': username,
                'password': password,
                'rememberme': rememberMe,
            },
            success: function (response) {
                if (response == "ok") {
                    window.location.href = LoginRedirectLink;
                } else {
                    console.log(response);
                    alert("Email-Id or password does not match");
                }
            }
        });
    }

});

//validation to add/update pg.
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
    if ($("#Zip").val().trim() == '' || $("#Zip").val().length != 6) {
        e.preventDefault();
        error = true;
        $("#Errzip").html("<p class='text-danger'>Please enter your postcode properly..</p>")
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

    if ($("#type").val() == 'pg/co-living') {
        if ($("#pggender").val() == "null") {
            $("#Errpggender").html("<p class='text-danger'>Please enter your gender preference..</p>")
        } else {
            $("#Errpggender").html("<p class='text-success'>Is correct...</p>");
        }
    }

    if ($("#OwnerId").val() == 'null') {
        e.preventDefault();
        error = true;
        $("#Errowner").html("<p class='text-danger'>Please select the Owner..</p>")
    } else {
        $("#Errowner").html("<p class='text-success'>Is correct...</p>");
    }

    if (error) {
        return false;
    }
});

//validation for Add/update user's
$("#addupdateUsers").click((e) => {
    var error = true;
    if ($("#name").val().trim() == "") {
        $('#nameErr').html("<p class='text-danger'>Please enter your name..</p>");
        e.preventDefault();
        error = false;
    } else {
        $('#nameErr').html("<p class='text-success'>*</p>");
    }
    if ($("#email").val() == "" || !(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test($("#email").val()))) {
        $('#emailErr').html("<p class='text-danger'>Please enter your email..</p>");
        e.preventDefault();
        error = false;
    } else {
        $('#emailErr').html("<p class='text-success'>*</p>");
    }
    if ($('input[name="gender"]:checked').val() == undefined) {
        $('#genderErr').html("<p class='text-danger'>Please select gender..</p>");
        e.preventDefault();
        error = false;
    } else {
        $('#genderErr').html("<p class='text-success'>*</p>");
    }
    if ($("#password").val().trim() == "" || $("#password").val().length <= 6) {
        $('#passwordErr').html("<p class='text-danger'>Please enter your password..</p>");
        e.preventDefault();
        error = false;
    } else {
        $('#Errpassword').html("<p class='text-success'>*</p>");
    }
    if ($("#country").val() == "null") {
        $('#countryErr').html("<p class='text-danger'>Please select country...</p>");
        e.preventDefault();
        error = false;
    } else {
        $('#countryErr').html("<p class='text-success'>*</p>");
    }
    if ($("#state").val() == "null") {
        $('#stateErr').html("<p class='text-danger'>Please select state..</p>");
        e.preventDefault();
        error = false;
    } else {
        $('#stateErr').html("<p class='text-success'>*</p>");
    }

    if ($("#city").val() == "null") {
        $('#cityErr').html("<p class='text-danger'>Please select city..</p>");
        e.preventDefault();
        error = false;
    } else {
        $('#cityErr').html("<p class='text-success'>*</p>");
    }
    if ($("#Address").val().trim() == "") {
        $('#addressErr').html("<p class='text-danger'>Please enter local address..</p>");
        e.preventDefault();
        error = false;
    } else {
        $('#addressErr').html("<p class='text-success'>*</p>");
    }
    if ($("#Zip").val().trim() == "") {
        $('#postcodeErr').html("<p class='text-danger'>Please enter post code..</p>");
        e.preventDefault();
        error = false;
    } else {
        $('#postcodeErr').html("<p class='text-success'>*</p>");
    }
    if ($("#type").val() == "null") {
        $('#typeErr').html("<p class='text-danger'>Please select Type..</p>");
        e.preventDefault();
        error = false;
    } else {
        $('#typeErr').html("<p class='text-success'>*</p>");
    }
    var phoneno = /^\d{10}$/;
    if ($("#contact").val().trim() == "" || !$("#contact").val().match(phoneno)) {
        $('#contactErr').html("<p class='text-danger'>Please Enter your contact no..</p>");
        e.preventDefault();
        error = false;
    } else {
        $('#contactErr').html("<p class='text-success'>*</p>");
    }
    if (!error) {
        return false;
    }
});

//validation for Add Property
$("#AddProp").click((e) => {
    var error = true;

    if ($("#propname").val().trim() == "") {
        $('#Errpropname').html("<p class='text-danger'>Please enter your name..</p>");
        e.preventDefault();
        error = false;
    } else {
        $('#Errpropname').html("<p class='text-success'>*</p>");
    }
    if ($("#shortdesc").val().trim() == "") {
        $('#Errshortdesc').html("<p class='text-danger'>Please enter your some dicription..</p>");
        e.preventDefault();
        error = false;
    } else {
        $('#Errshortdesc').html("<p class='text-success'>*</p>");
    }

    if ($("#longdesc").val().trim() == "") {
        $('#Errlongdesc').html("<p class='text-danger'>Please enter all facilities..</p>");
        e.preventDefault();
        error = false;
    } else {
        $('#Errlongdesc').html("<p class='text-success'>*</p>");
    }
    if ($("#country").val() == "Choose...") {
        $('#Errcountry').html("<p class='text-danger'>Please enter your country..</p>");
        e.preventDefault();
        error = false;
    } else {
        $('#Errcountry').html("<p class='text-success'>*</p>");
    }
    if ($("#state").val() == null) {
        $('#Errstate').html("<p class='text-danger'>Please enter your state..</p>");
        e.preventDefault();
        error = false;
    } else {
        $('#Errstate').html("<p class='text-success'>*</p>");
    }
    if ($("#city").val() == null) {
        $('#Errcity').html("<p class='text-danger'>Please enter your city..</p>");
        e.preventDefault();
        error = false;
    } else {
        $('#Errcity').html("<p class='text-success'>*</p>");
    }
    if ($("#inputAddress").val().trim() == "") {
        $('#Erraddress').html("<p class='text-danger'>Please enter your local Address..</p>");
        e.preventDefault();
        error = false;
    } else {
        $('#Erraddress').html("<p class='text-success'>*</p>");
    }
    if ($("#inputZip").val().trim() == "" || $("#inputZip").val().length != 6) {
        $('#Errzip').html("<p class='text-danger'>Please enter your pincode..</p>");
        e.preventDefault();
        error = false;
    } else {
        $('#Errzip').html("<p class='text-success'>*</p>");
    }
    if ($("#area").val().trim() == "") {
        $('#Errarea').html("<p class='text-danger'>Please enter area..</p>");
        e.preventDefault();
        error = false;
    } else {
        $('#Errarea').html("<p class='text-success'>*</p>");
    }
    if ($("#room").val() == "Choose...") {
        $('#Errrooms').html("<p class='text-danger'>Please enter no rooms..</p>");
        e.preventDefault();
        error = false;
    } else {
        $('#Errrooms').html("<p class='text-success'>*</p>");
    }
    if ($("#catagory").val() == "Choose...") {
        $('#Errcat').html("<p class='text-danger'>Please select your catagory..</p>");
        e.preventDefault();
        error = false;
    } else {
        $('#Errcat').html("<p class='text-success'>*</p>");
    }
    if ($("#type").val() == null) {
        $('#Errtype').html("<p class='text-danger'>Please select your type ..</p>");
        e.preventDefault();
        error = false;
    } else {
        $('#Errtype').html("<p class='text-success'>*</p>");
    }
    if ($("#price").val().trim() == "" || !isNumber($("#price").val())) {
        $('#Errprice').html("<p class='text-danger'>Please enter price..</p>");
        e.preventDefault();
        error = false;
    } else {
        $('#Errprice').html("<p class='text-success'>*</p>");
    }

    if ($("#RentalId").val() == null) {
        $('#Errrent').html("<p class='text-danger'>Please select rentel ..</p>");
        e.preventDefault();
        error = false;
    } else {
        $('#Errrent').html("<p class='text-success'>*</p>");
    }


    if ($("#userstatus").val() == null) {

        $error = true;
        $('#Errstatus').html("<p class='text-danger'>Please select status ..</p>");
        e.preventDefault();
        error = false;
    } else {
        $('#Errstatus').html("<p class='text-success'>*</p>");
    }
    if ($("#rating").val() == 0) {
        $('#Errrating').html("<p class='text-danger'>Please select rating ..</p>");
        e.preventDefault();
        error = false;
    } else {
        $('#Errrating').html("<p class='text-success'>*</p>");
    }

    if ($("#OwnerId").val() == "AddUser") {

        if ($("#username").val() == "") {
            $('#Errusername').html("<p class='text-danger'>Please enter owner name..</p>");
            e.preventDefault();
            error = false;
        } else {
            $('#Errusername').html("<p class='text-success'>*</p>");
        }

        if ($("#email").val() == "" || !(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test($("#email").val()))) {
            $('#Erremail').html("<p class='text-danger'>Please enter valid email ..</p>");
            e.preventDefault();
            error = false;
        } else {
            $('#Erremail').html("<p class='text-success'>*</p>");
        }
        if ($("#female").is(":checked") || $("#male").is(":checked")) {
            $("#genderErr").html("<p class='text-success'>Is correct..</p>");
        } else {
            e.preventDefault();
            error = true;
            $("#genderErr").html("<p class='text-danger'>Please select your gender..</p>");
        }
        if ($("#password").val().trim() == "" || $("#password").val().length <= 6) {
            $('#Errpassword').html("<p class='text-danger'>Please enter password ..</p>");
            e.preventDefault();
            error = false;
        } else {
            $('#Errpassword').html("<p class='text-success'>*</p>");
        }

        if ($("#usercountry").val() == "Choose...") {
            $('#Errusercountry').html("<p class='text-danger'>Please select user country ..</p>");
            e.preventDefault();
            error = false;
        } else {
            $('#Errusercountry').html("<p class='text-success'>*</p>");
        }

        if ($("#userstate").val() == null) {
            $('#Erruserstate').html("<p class='text-danger'>Please select user state ..</p>");
            e.preventDefault();
            error = false;
        } else {
            $('#Erruserstate').html("<p class='text-success'>*</p>");
        }


        if ($("#usercity").val() == null) {
            $('#Errusercity').html("<p class='text-danger'>Please select user city ..</p>");
            e.preventDefault();
            error = false;
        } else {
            $('#Errusercity').html("<p class='text-success'>*</p>");
        }


        if ($("#userAddress").val().trim() == "") {
            $('#Erruseraddress').html("<p class='text-danger'>Please enter user address ..</p>");
            e.preventDefault();
            error = false;
        } else {
            $('#Erruseraddress').html("<p class='text-success'>*</p>");
        }

        if ($("#contact").val().trim() == "") {
            $('#Errcontact').html("<p class='text-danger'>Please enter user contact ..</p>");
            e.preventDefault();
            error = false;
        } else {
            $('#Errcontact').html("<p class='text-success'>*</p>");
        }

    }
    if (!error) {
        return false;
    }
});

//Validation For Update Property
$("#update").click((e) => {
    var error = true;
    if ($("#propname").val().trim() == "") {
        $('#Errpropname').html("<p class='text-danger'>Please enter your name..</p>");
        e.preventDefault();
        error = false;
    } else {
        $('#Errpropname').html("<p class='text-success'>*</p>");
    }
    if ($("#shortdesc").val().trim() == "") {
        $('#Errshortdesc').html("<p class='text-danger'>Please enter your some dicription..</p>");
        e.preventDefault();
        error = false;
    } else {
        $('#Errshortdesc').html("<p class='text-success'>*</p>");
    }

    if ($("#longdesc").val().trim() == "") {
        $('#Errlongdesc').html("<p class='text-danger'>Please enter all facilities..</p>");
        e.preventDefault();
        error = false;
    } else {
        $('#Errlongdesc').html("<p class='text-success'>*</p>");
    }
    if ($("#country").val() == "Choose...") {
        $('#Errcountry').html("<p class='text-danger'>Please enter your country..</p>");
        e.preventDefault();
        error = false;
    } else {
        $('#Errcountry').html("<p class='text-success'>*</p>");
    }
    if ($("#state").val() == null) {
        $('#Errstate').html("<p class='text-danger'>Please enter your state..</p>");
        e.preventDefault();
        error = false;
    } else {
        $('#Errstate').html("<p class='text-success'>*</p>");
    }
    if ($("#city").val() == null) {
        $('#Errcity').html("<p class='text-danger'>Please enter your city..</p>");
        e.preventDefault();
        error = false;
    } else {
        $('#Errcity').html("<p class='text-success'>*</p>");
    }
    if ($("#inputAddress").val().trim() == "") {
        $('#Erraddress').html("<p class='text-danger'>Please enter your local Address..</p>");
        e.preventDefault();
        error = false;
    } else {
        $('#Erraddress').html("<p class='text-success'>*</p>");
    }
    if ($("#inputZip").val().trim() == "" || $("#inputZip").val().trim().length != 6) {
        $('#Errzip').html("<p class='text-danger'>Please enter your 6 digit pincode..</p>");
        e.preventDefault();
        error = false;
    } else {
        $('#Errzip').html("<p class='text-success'>*</p>");
    }
    if ($("#area").val().trim() == "") {
        $('#Errarea').html("<p class='text-danger'>Please enter area..</p>");
        e.preventDefault();
        error = false;
    } else {
        $('#Errarea').html("<p class='text-success'>*</p>");
    }
    if ($("#room").val() == "Choose...") {
        $('#Errrooms').html("<p class='text-danger'>Please enter no rooms..</p>");
        e.preventDefault();
        error = false;
    } else {
        $('#Errrooms').html("<p class='text-success'>*</p>");
    }
    if ($("#catagory").val() == "Choose...") {
        $('#Errcat').html("<p class='text-danger'>Please select your catagory..</p>");
        e.preventDefault();
        error = false;
    } else {
        $('#Errcat').html("<p class='text-success'>*</p>");
    }
    if ($("#type").val() == null) {
        $('#Errtype').html("<p class='text-danger'>Please select your type ..</p>");
        e.preventDefault();
        error = false;
    } else {
        $('#Errtype').html("<p class='text-success'>*</p>");
    }
    if ($("#price").val().trim() == "" || !isNumber($("#price").val())) {
        $('#Errprice').html("<p class='text-danger'>Please enter price..</p>");
        e.preventDefault();
        error = false;
    } else {
        $('#Errprice').html("<p class='text-success'>*</p>");
    }
    if ($("#OwnerId").val() == null) {
        $('#Errowner').html("<p class='text-danger'>Please select owner..</p>");
        e.preventDefault();
        error = false;
    } else {
        $('#Errowner').html("<p class='text-success'>*</p>");
    }
    if ($("#RentalId").val() == null) {
        $('#Errrent').html("<p class='text-danger'>Please select rentel ..</p>");
        e.preventDefault();
        error = false;
    } else {
        $('#Errrent').html("<p class='text-success'>*</p>");
    }


    if ($("#userstatus").val() == null) {
        $error = true;
        $('#Errstatus').html("<p class='text-danger'>Please select status ..</p>");
        e.preventDefault();
        error = false;
    } else {
        $('#Errstatus').html("<p class='text-success'>*</p>");
    }
    if ($("#rating").val() == 0) {
        $('#Errrating').html("<p class='text-danger'>Please select rating ..</p>");
        e.preventDefault();
        error = false;
    } else {
        $('#Errrating').html("<p class='text-success'>*</p>");
    }

    if (!error) {
        return false;
    }
});

//change state on change of country
function selectstate() {
    var country = $('#country').val();
    $.ajax({
        type: 'POST',
        data: {
            'action': "selectstate",
            'country': country,
        },
        success: function (response) {
            $('#state').html('<option selected disabled value="">Choose...</option>' + response);
        }
    });
}

//change city on change of state
function selectcity() {
    var state = $('#state').val();
    $.ajax({
        type: 'POST',
        data: {
            'action': "selectcity",
            'state': state,
        },
        success: function (response) {
            $('#city').html('<option selected disabled value="">Choose...</option>' + response);
        }
    });
}

//for selecting state on select of country for user
function userselectstate() {
    var country = $('#usercountry').val();
    $.ajax({
        type: 'POST',
        data: {
            'action': "userselectstate",
            'country': country,
        },
        success: function (response) {
            $('#userstate').html('<option selected disabled value="">Choose...</option>' + response);
        }
    });
}

//for selecting city on select of state for user
function userselectcity() {
    var state = $('#userstate').val();
    $.ajax({
        type: 'POST',
        data: {
            'action': "userselectcity",
            'state': state,
        },
        success: function (response) {
            $('#usercity').html('<option selected disabled value="">Choose...</option>' + response);
        }
    });
}

$("#userdetail").hide();
function openuserdiv() {

    if ($("#OwnerId").val() == "AddUser") {
        $("#userdetail").show();
    } else {
        $("#userdetail").hide();
    }

}

//searching for properties
function searchinginProperties() {
    var $catagory = $("#catagory").val();
    var searchprop = $("#search").val();

    $.ajax({
        type: 'POST',
        data: {
            'action': "searchingproperty",
            'searchprop': searchprop,
            'catagory': $catagory,
        },
        success: function (response) {
            response = JSON.parse(response);
            var pageid = id;
            $('#showtable').html('');
            if (isNaN(pageid)) {
                pageid = 1
            }
            var results_per_page = 3;
            var page_first_result = (pageid - 1) * results_per_page;
            var number_of_result = response.length;
            var number_of_page = Math.ceil(number_of_result / results_per_page);
            var data = response.slice(parseInt(page_first_result), parseInt(page_first_result + results_per_page));
            var i = page_first_result + 1;
            data.forEach(element => {
                if (element.Rating) {
                    Ratingjson = JSON.parse(element.Rating);
                    var count = 0;
                    var sum = 0;
                    Ratingjson.forEach(e => {
                        sum = sum + e.rating;
                        count++;
                    });
                    if (sum != 0) {
                        var avg = (sum / count);
                        var avgrating = Math.round(avg);
                    }
                }
                if (avgrating == undefined) {
                    avgrating = 0;
                }
                var photo = element.Photos.split(",");

                var username = '';

                if (element.Owner_Id != null) {
                    username = "<b>Owner Name : </b>" + element.Owner_Id + "<br>";
                }
                if (element.Rental_Id != null) {
                    var username = username + "<b>Rental Name : </b>" + element.Rental_Id;
                }
                var propertydatarow = `
            <tr class='table-primary'>
            <td scope='row'>${i}</td>
            <td><img class='mt-3 w-100 rounded border border-3 border-dark p-1 ' src='https://propertyimgbucket1.s3.ap-south-1.amazonaws.com/images/propertyImg/property${element.property_id}/${photo[0]}' width='100px' height = '100px'></td>
            <td> <b>${element.property_name}</b><br>${element.sort_descreption}<br><b>Facilities : </b>${element.long_discription}<br><b>Rooms : </b>${element.Rooms}<br><b>Area : </b>${element.Area} sq/ft<br><b>Price : </b>${element.price}<br><b>Rating : </b>${avgrating}</td>
            <td><b>Type : </b>${element.catagories}</td>
            <td><b>Location :- </b><br> <b>Country : </b>${element.country} <br><b> State : </b>${element.state}<br> <b> City :  </b>${element.city} <br> <b>Street Adddress :  </b>${element.local_address}<br> <b> Postcode :  </b>${element.postcode}</td>
            <td><b>${element.status}</b></td>
            <td>${username}</td>
            <td>${element.date_of_registration}</td>
            <td class=''><button name='deletebtn' id='delprop${element.property_id}' class='delprop btn btn-outline-danger' type='button'><img src='https://www.svgrepo.com/show/490950/delete.svg' width='30px' height='30px' alt='delete icon'></button></td>
            <td class=''><button name='updatebtn' id='updprop${element.property_id}' class='updprop btn btn-outline-warning' type='button'><img src='https://www.svgrepo.com/show/422395/edit-interface-multimedia.svg' width='30px' height='30px' alt='update icon'></button></td>
            </tr>
            `;
                i++;
                $('#showtable').append(propertydatarow);
            });
            var x;
            if (pageid == NaN || pageid == 1) {
                x = 1;
            } else {
                x = pageid - 1;
            }
            $('#pagination').html('');
            $('#pagination').append(`
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
    });

}

//pagination while searching element in Property
$(document).on('click', '.propertypagelink', function () {
    id = (this.id).slice(4);
    searchinginProperties();
});

//searching in Pgs.
function searchinginPg() {
    var pageid = parseInt(id);

    var $catagory = $("#catagory").val();
    var searchprop = $("#search").val();
    $.ajax({
        type: 'POST',
        data: {
            'action': "SearchPG",
            'searchprop': searchprop,
            'catagory': $catagory,
            'pageid': pageid,
        },
        success: function (response) {
            $("#showtable").html('');
            if (response != '') {
                response = JSON.parse(response);
                if (isNaN(pageid)) {
                    pageid = 1
                }
                var results_per_page = 3;
                var page_first_result = (pageid - 1) * results_per_page;
                var number_of_result = response.length;
                var number_of_page = Math.ceil(number_of_result / results_per_page);
                var data = response.slice(parseInt(page_first_result), parseInt(page_first_result + results_per_page));
                var i = page_first_result + 1;
                data.forEach(element => {
                    var photo = element.Photos.split(',');
                    var owner = '';
                    if (element.Owner_Id) {
                        owner = element.Owner_Id;
                    } else {
                        owner = "No Owner"
                    }
                    var pgdatarow = ` 
               <tr class='table-primary' >
                <td scope='row'>${i}</td>
                <td><img class='mt-3 w-100 rounded border border-3 border-dark p-1 ' src='https://propertyimgbucket1.s3.ap-south-1.amazonaws.com/images/Pgimages/pg${element.pg_id}/${photo[0]}' width='100px' height = '100px'></td>
                <td><b>Name : </b> <b>${element.pg_name}</b><br>${element.sort_descreption}<br><b>Facilities : </b>${element.Facilities}<br><b>Rooms Avalilable: </b>${element.Room_capacity}<br><b>Total Rooms : </b>${element.Rooms}</td>
                <td><b>For:  </b> ${element.type}</td>
                <td><b>Location : </b><br><b>Country :  </b>${element.country}<br><b> State : </b>${element.state}<br><b> City : </b>${element.city}<br><b>Street Adddress : </b>${element.local_address}<br>Postcode : ${element.postcode}</td>
                <td><b>${element.status}</b></td>
                <td>${owner}</td>
                <td>${element.date_of_registration}</td>
                <td class=''><button name='deletebtn' id='delpg${element.pg_id}' class='delpg btn btn-outline-danger' type='button'><img src='https://www.svgrepo.com/show/490950/delete.svg' width='30px' height='30px' alt='delete icon'></button></td>
                <td class='' ><button name='updatebtn' id='updpg${element.pg_id}' class='updpg btn btn-outline-warning' type='button'><img src='https://www.svgrepo.com/show/422395/edit-interface-multimedia.svg' width='30px' height='30px' alt='update icon'></button></td>
                </tr>`;
                    i++;
                    $("#showtable").append(pgdatarow);
                });
                var x;
                if (pageid == NaN || pageid == 1) {
                    x = 1;
                } else {
                    x = pageid - 1;
                }
                $('#pagination').html('');
                $('#pagination').append(`
                <div class='d-flex justify-content-center'>
                <ul class='pagination mt-3' id = 'pagelist'>
                <li class='page-item'  style = 'list-style-type: none;'><a class='pgpagelink page-link' href='#' id = 'link${x}'>Previous</a></li>`);
                for (var pages = 1; pages <= number_of_page; pages++) {
                    $('.pagination').append(`<li class='page-item'  style = 'list-style-type: none; '><a class='pgpagelink page-link' id = 'link${pages}' href='#' > ${pages} </a></li>`);
                }
                var y;
                if (pageid == number_of_page) {
                    y = parseInt(pageid);
                } else {
                    y = parseInt(pageid) + 1;
                }
                $('.pagination').append(`<li class='page-item'  style = 'list-style-type: none; '><a class='pgpagelink page-link' href='#' id = 'link${y}' >Next</a></li>`);
                $('.pagination').append(`</ul></div>`);
                if (pageid > number_of_page) {
                    $("#link1").trigger('click');
                }
                if ($("#link" + pageid).text() != "Previous") {
                    $("#link" + pageid).addClass('text-danger');
                }
            } else {
                $('#showtable').html("<div class='ms-2 text-danger text-center fs-3'><p class='text-center'>Sorry, No Pg found :( </p></div>");
            }
        }
    });
 }

//pagination while searching element in PG
$(document).on('click', '.pgpagelink', function () {
    id = (this.id).slice(4);
    searchinginPg();
});

//Search in User's
function searchinUser() {
    var $type = $("#type").val();
    var searchuser = $("#search").val();
    $.ajax({
        type: 'POST',
        data: {
            'action': "searchUser",
            'searchuser': searchuser,
            'type': $type,
        },
        success: function (response) {
            if (response != '') {
                response = JSON.parse(response);
                $("#listUsers").html('');
                var pageid = id;
                if (isNaN(pageid)) {
                    pageid = 1
                }
                var results_per_page = 3;
                var page_first_result = (pageid - 1) * results_per_page;
                var number_of_result = response.length;
                var number_of_page = Math.ceil(number_of_result / results_per_page);
                var data = response.slice(parseInt(page_first_result), parseInt(page_first_result + results_per_page));
                var i = page_first_result + 1;
                data.forEach(element => {
                    let Userdatarow = `
                    <tr class='table-primary'>
                    <td scope='row'>${i}</td>
                    <td scope='row'><img class='mt-3 w-100 rounded border border-3 border-dark p-1 ' src='https://propertyimgbucket1.s3.ap-south-1.amazonaws.com/images/images/user${element.user_id}/${element.profile_pic}'width='100px' height='100px' ></td>
                    <td scope='row'><b>Name : </b>${element.name}<br><b>Email : </b>${element.email}<br><b>Contact : </b>${element.contact_no}</td>
                    <td scope='row'><b>Local Address : </b>${element.current_address}<br><b>	City : </b>${element.city}<br><b>State : </b>${element.state}<br><b>Country : </b>${element.country}</td>
                    <td scope='row'>${element.Owner_Id}</td>
                    <td scope='row'>${element.Rentel_Id}</td>
                    <td class=''><button name='deletebtn' id='deluser${element.user_id}' class='deluser btn btn-outline-danger' type='button'><img src='https://www.svgrepo.com/show/490950/delete.svg' width='30px' height='30px' alt='delete icon'></button></td>
                    <td class='' ><button name='updatebtn' id='upduser${element.user_id}' class='upduser btn btn-outline-warning' type='button'><img src='https://www.svgrepo.com/show/422395/edit-interface-multimedia.svg' width='30px' height='30px' alt='update icon'></button></td></tr>`;
                    i++;
                    $("#listUsers").append(Userdatarow);
                });

                var x;
                if (pageid == NaN || pageid == 1) {
                    x = 1;
                } else {
                    x = pageid - 1;
                }
                $('#pagination').html('');
                $('#pagination').append(`
            <div class='d-flex justify-content-center'>
            <ul class='pagination mt-3' id = 'pagelist'>
            <li class='page-item'><a class='userpagelink page-link' href='#' id = 'link${x}'>Previous</a></li>`);
                for (var pages = 1; pages <= number_of_page; pages++) {
                    $('#pagelist').append(`<li class='page-item'><a class='userpagelink page-link' id = 'link${pages}' href='#' > ${pages} </a></li>`);
                }
                var y;
                if (pageid == number_of_page) {
                    y = parseInt(pageid);
                } else {
                    y = parseInt(pageid) + 1;
                }
                $('#pagelist').append(`<li class='page-item'><a class='userpagelink page-link' href='#' id = 'link${y}' >Next</a></li>`);
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

//pagination while searching element in PG
$(document).on('click', '.userpagelink', function () {
    id = (this.id).slice(4);
    searchinUser();
});

//Deleting Property
$(document).on("click", ".delprop", function () {
    var delid = (this.id).slice(7);
    console.log(delid);
    var msg = "Are you sure you want to delete this property..?";
    if (confirm(msg) == true) {
        $.ajax({
            type: 'POST',
            data: {
                'action': "delProperty",
                'delid': delid,
            },
            success: function (response) {
                location.reload();
                alert("Property is deleted...");
            }
        });
    } else {
        return false;
    }
});

//Delete PG 
$(document).on("click", ".delpg", function () {
    var delid = (this.id).slice(5);
    var msg = "Are you sure you want to delete this property..?";
    if (confirm(msg) == true) {
        $.ajax({
            type: 'POST',
            data: {
                'action': "delpg",
                'delid': delid,
            },
            success: function (response) {
                location.reload();
                alert("Pg is deleted...");
            }
        });
    } else {
        return false;
    }
});

//Delete User's
$(document).on("click", ".deluser", function () {
    var delid = (this.id).slice(7);
    var msg = "Are you sure you want to delete this user..?";
    if (confirm(msg) == true) {
        $.ajax({
            type: 'POST',
            data: {
                'action': "deluser",
                'delid': delid,
            },
            success: function (response) {
                location.reload();
                toastr.success('User is deleted');
            }
        });
    } else {
        return false;
    }
});

//link to update a user..
$(document).on('click', '.upduser', function () {
    var updid = (this.id).slice(7);
    console.log(updid);
    window.location.href = linkforupdateuser + "?id=" + updid;
});

//linkto update Property  
$(document).on('click', '.updprop', function () {
    var updid = (this.id).slice(7);
    window.location.href = linkforUpdateProperty + updid;
});

//linkto update PG's
$(document).on('click', '.updpg', function () {
    var updid = (this.id).slice(5);
    window.location.href = linktoupdatepg + updid;
});

//Logout btn
$("#logout").click(() => {
    if (confirm('Are you sure you want to Logout?')) {
        $.ajax({
            type: 'POST',
            data: {
                'action': "adminlogout",
            },
            success: function (response) {
                if (response == 'success') {
                    window.location.href = 'http://ops.localhost//PHPOPS/Application/Admin/';
                } else {
                }
            }
        });
    }
});