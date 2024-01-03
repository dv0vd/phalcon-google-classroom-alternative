const ERROR_MESSAGE = "<div class='alert alert-danger' role='alert'>Произошла ошибка... Обновите страницу или повторите попытку позднее. Если проблема не исчезнет обратитесь в службу поддержки.</div>";

function setFooter() {
  if($(document).height() <= $(window).height()) {
      $("footer").addClass("fixed-bottom");
  } else {
      $("footer").removeClass("fixed-bottom");
  }
}

$(window).resize(function () {
  setFooter();
});

$(document).ready(function () {
  setFooter();
});

$(document).mousemove(function (e) {
  setFooter();
});

$(document).scroll(function (e) {
  setFooter();
});

$("body").on('click','.removeMember',function (e) {
  var member_id = e.target.id.substr(12);
  $('#memberIdModal').text(member_id);
  $('#removeMemberModal').modal('toggle');
});

$("body").on('click','.editModalRemoveUploadedFile',function (e) {
  e.preventDefault();
  $('#editMaterialsFileErrors').empty();
  var id = $(this).attr('id');
  var file_id = $(this).attr('id').substr(27);
  var tokenKey = $("meta[name='csrf-param']").attr('content');
  var token = $("meta[name='csrf-token']").attr('content');
  var postData = {
    fileId: file_id,
    courseId: $("#courseId").text()
  };
  postData[tokenKey] = token;
  $.ajax({
    url: '/profile/course/removeUploadedFile',
    type: "post",
    dataType: 'JSON',
    data: postData,
    success: function (data) {
      $("meta[name='csrf-param']").attr('content', data[0].tokenKey);
      $("meta[name='csrf-token']").attr('content', data[0].token);
      if((data.length == 2) && (data[1].result == 'success')) {
        $('#' + id).remove();
      } else {
        for(var i = 1; i < data.length; i++) {
          $('#editMaterialsFileErrors').append("<div class='alert alert-danger' role='alert'>" + data[i].message + "</div>");
        }
      }
    },
    error: function (data) {
      $('#editMaterialsFileErrors').append(ERROR_MESSAGE);
    }
  });
});

$("body").on('click','.editModalRemoveUploadedFileTask',function (e) {
  e.preventDefault();
  $('#editTasksFileErrors').empty();
  var id = $(this).attr('id');
  var file_id = $(this).attr('id').substr(31);
  var tokenKey = $("meta[name='csrf-param']").attr('content');
  var token = $("meta[name='csrf-token']").attr('content');
  var postData = {
    fileId: file_id,
    courseId: $("#courseId").text()
  };
  postData[tokenKey] = token;
  $.ajax({
    url: '/profile/course/removeUploadedFileTask',
    type: "post",
    dataType: 'JSON',
    data: postData,
    success: function (data) {
      $("meta[name='csrf-param']").attr('content', data[0].tokenKey);
      $("meta[name='csrf-token']").attr('content', data[0].token);
      if((data.length == 2) && (data[1].result == 'success')) {
        $('#' + id).remove();
      } else {
        for(var i = 1; i < data.length; i++) {
          $('#editTasksFileErrors').append("<div class='alert alert-danger' role='alert'>" + data[i].message + "</div>");
        }
      }
    },
    error: function (data) {
      $('#editTasksFileErrors').append(ERROR_MESSAGE);
    }
  });
});

$("body").on('click','.removeMaterial',function (e) {
  var material_id = e.target.id.substr(14);
  $('#materialIdModal').text(material_id);
  $('#removeMaterialModal').modal('toggle');
});

$("body").on('click','.removeTask',function (e) {
  var task_id = e.target.id.substr(10);
  $('#taskIdModal').text(task_id);
  $('#removeTaskModal').modal('toggle');
});

$("body").on('click','.editMaterial',function (e) {
  $('#editMaterialForm').trigger('reset');
  $('#materialsTabMessages').empty();
  $('#editMaterialModal').modal('toggle');
  var material_id = e.target.id.substr(12);
  $('#editMaterialsFormMaterialCode').val(material_id);
  var tokenKey = $("meta[name='csrf-param']").attr('content');
  var token = $("meta[name='csrf-token']").attr('content');
  var postData = {
    materialId: material_id,
    courseId: $("#courseId").text()
  };
  postData[tokenKey] = token;
  $.ajax({
    url: '/profile/course/getMaterialData',
    type: "post",
    dataType: 'JSON',
    data: postData,
    // beforeSend: function() {
    //   $('#addMaterialBtn').prop('disabled', true);
    // },
    success: function (data) {
      // $('#addMaterialBtn').prop('disabled', false);
      $("meta[name='csrf-param']").attr('content', data[0].tokenKey);
      $("meta[name='csrf-token']").attr('content', data[0].token);
      if((data.length == 2) && (data[1].result == 'success')) {
        $('#editMaterialTitle').val(data[1].data.title);
        editor2.setData(data[1].data.description);
        for(var i = 0; i < data[1].files.length; i++ ) {
          let name = data[1].files[i].location.split('__-__');
          $('#editMaterialUploadedFiles').append("<a href='#' class='editModalRemoveUploadedFile' id='editModalRemoveUploadedFile" + data[1].files[i].id +"' ><p>" + name[1] +" (Удалить)</p></a>")
        }
      } else {
        setTimeout(function(){$('#editMaterialModal').modal('toggle')}, 600);
        for(var i = 1; i < data.length; i++) {
          $('#materialsTabMessages').append("<div class='alert alert-danger' role='alert'>" + data[i].message + "</div>");
        }
      }
    },
    error: function (data) {
      getMaterials();
      setTimeout(function(){$('#editMaterialModal').modal('toggle')}, 600);
      $('#materialsTabMessages').append(ERROR_MESSAGE);
    }
  });
});

$("body").on('click','.editTask',function (e) {
  $('#editTaskForm').trigger('reset');
  $('#tasksTabMessages').empty();
  $('#editTaskModal').modal('toggle');
  var task_id = e.target.id.substr(8);
  $('#editTasksFormTaskCode').val(task_id);
  var tokenKey = $("meta[name='csrf-param']").attr('content');
  var token = $("meta[name='csrf-token']").attr('content');
  var postData = {
    taskId: task_id,
    courseId: $("#courseId").text()
  };
  postData[tokenKey] = token;
  $.ajax({
    url: '/profile/course/getTaskData',
    type: "post",
    dataType: 'JSON',
    data: postData,
    // beforeSend: function() {
    //   $('#addMaterialBtn').prop('disabled', true);
    // },
    success: function (data) {
      // $('#addMaterialBtn').prop('disabled', false);
      $("meta[name='csrf-param']").attr('content', data[0].tokenKey);
      $("meta[name='csrf-token']").attr('content', data[0].token);
      if((data.length == 2) && (data[1].result == 'success')) {
        $('#editTaskTitle').val(data[1].data.title);
        $('#editTaskScore').val(data[1].data.score);
        editorEditTask.setData(data[1].data.description);
        for(var i = 0; i < data[1].files.length; i++ ) {
          let name = data[1].files[i].location.split('__-__');
          $('#editTaskUploadedFiles').append("<a href='#' class='editModalRemoveUploadedFileTask' id='editModalRemoveUploadedFileTask" + data[1].files[i].id +"' ><p>" + name[1] +" (Удалить)</p></a>")
        }
      } else {
        setTimeout(function(){$('#editTaskModal').modal('toggle')}, 600);
        for(var i = 1; i < data.length; i++) {
          $('#tasksTabMessages').append("<div class='alert alert-danger' role='alert'>" + data[i].message + "</div>");
        }
      }
    },
    error: function (data) {
      getMaterials();
      setTimeout(function(){$('#editTaskModal').modal('toggle')}, 600);
      $('#tasksTabMessages').append(ERROR_MESSAGE);
    }
  });
});

$("body").on('change','.selectMemeberRole',function (e) {
  var member_id = e.target.id.substr(16);
  $('#changeMemberRoleId').text(member_id);
  $('#changeMemberRoleValue').text(this.value);
  $('#changeMemberRoleModal').modal('toggle');
});

$("#signupForm").bind("submit", function (e) {
  e.preventDefault();
  $('#messages').empty();
  if($('#password').val().length < 8) {
    $('#messages').append("<div class='alert alert-danger' role='alert'>Длина пароля должна быть минимум 8 символов!</div>");
    $('#messages').show();
    return;
  }
  if($('#password').val() != $('#confirmPassword').val()) {
    $('#messages').append("<div class='alert alert-danger' role='alert'>Пароли не совпадают!</div>");
    $('#messages').show();
    return;
  }
  url = $("#signupForm").attr("action");
  var tokenKey = $("meta[name='csrf-param']").attr('content');
  var token = $("meta[name='csrf-token']").attr('content');
  var postData = {
    email: $("#email").val(),
    password:  $("#password").val(),
    confirmPassword: $("#confirmPassword").val()
  };
  postData[tokenKey] = token;
  $.ajax({
    url: url,
    type: "post",
    dataType: 'JSON',
    data: postData,
    success: function (data) {
      $("meta[name='csrf-param']").attr('content', data[0].tokenKey);
      $("meta[name='csrf-token']").attr('content', data[0].token);
      if((data.length == 2) && (data[1].result == 'success')) {
        $('#signupConfirmation').modal('show');
        $('#emailNotification').text(data[1].email);
      } else {
        for(var i = 1; i < data.length; i++) {
          $('#messages').append("<div class='alert alert-danger' role='alert'>" + data[i].message + "</div>");
        }
        $('#messages').show();
      }
    },
    error: function (data) {
      $('#messages').append(ERROR_MESSAGE);
      $('#messages').show();
    }
  });
});

$("#changeDataSignup").click(function (e) {
  e.preventDefault();
  $('#signupConfirmation').modal('toggle');
});

$("#sendAgainSignup").click(function (e) {
  e.preventDefault();
  $('#messages').empty();
  url = '/signup/resend';
  var tokenKey = $("meta[name='csrf-param']").attr('content');
  var token = $("meta[name='csrf-token']").attr('content');
  var postData = {
    email: $("#email").val(),
  };
  postData[tokenKey] = token;
  $.ajax({
    url: url,
    type: "post",
    dataType: 'JSON',
    data: postData,
    success: function (data) {
      $("meta[name='csrf-param']").attr('content', data[0].tokenKey);
      $("meta[name='csrf-token']").attr('content', data[0].token);
      if((data.length == 2) && (data[1].result == 'success')) {
        $('#signupConfirmation').modal('toggle');
        setTimeout(function(){$('#signupConfirmation').modal('toggle')}, 600);
      } else {
        for(var i = 1; i < data.length; i++) {
          $('#messages').append("<div class='alert alert-danger' role='alert'>" + data[i].message + "</div>");
        }
        $('#signupConfirmation').modal('toggle');
        $('#messages').show();
      }
    },
    error: function (data) {
      $('#signupConfirmation').modal('toggle');
      $('#messages').append("<div class='alert alert-danger' role='alert'>Произошла ошибка... Повторите попытку позднее</div>");
      $('#messages').show();
    }
  });
});

$("#loginForm").bind("submit", function (e) {
  e.preventDefault();
  $('#messages').empty();
  if($('#password').val().length < 8) {
    $('#messages').append("<div class='alert alert-danger' role='alert'>Длина пароля должна быть минимум 8 символов!</div>");
    $('#messages').show();
    return;
  }
  var tokenKey = $("meta[name='csrf-param']").attr('content');
  var token = $("meta[name='csrf-token']").attr('content');
  var postData = {
    email: $("#email").val(),
    password:  $("#password").val()
  };
  postData[tokenKey] = token;
  url = $("#loginForm").attr("action");
  $.ajax({
    url: url,
    type: "post",
    dataType: 'JSON',
    data: postData,
    success: function (data) {
      $("meta[name='csrf-param']").attr('content', data[0].tokenKey);
      $("meta[name='csrf-token']").attr('content', data[0].token);
      if((data.length == 2) && (data[1].result == 'success')) {
        window.location.href = data[1].redirect;
      } else {
        for(var i = 1; i < data.length; i++) {
          $('#messages').append("<div class='alert alert-danger' role='alert'>" + data[i].message + "</div>");
        }
        $('#messages').show();
      }
    },
    error: function (data) {
      $('#messages').append(ERROR_MESSAGE);
      $('#messages').show();
    }
  });
});

$("#resetPasswordForm").bind("submit", function (e) {
  e.preventDefault();
  $('#messages').empty();
  url = $("#resetPasswordForm").attr("action");
  var tokenKey = $("meta[name='csrf-param']").attr('content');
  var token = $("meta[name='csrf-token']").attr('content');
  var postData = {
    email: $("#email").val(),
  };
  postData[tokenKey] = token;
  $.ajax({
    url: url,
    type: "post",
    dataType: 'JSON',
    data: postData,
    success: function (data) {
      $("meta[name='csrf-param']").attr('content', data[0].tokenKey);
      $("meta[name='csrf-token']").attr('content', data[0].token);
      if((data.length == 2) && (data[1].result == 'success')) {
        $('#resetConfirmation').modal('show');
        $('#emailNotification').text(data[1].email);
      } else {
        for(var i = 1; i < data.length; i++) {
          $('#messages').append("<div class='alert alert-danger' role='alert'>" + data[i].message + "</div>");
        }
        $('#messages').show();
      }
    },
    error: function (data) {
      $('#messages').append(ERROR_MESSAGE);
      $('#messages').show();
    }
  });
});

$("#changeDataLogin").click(function (e) {
  e.preventDefault();
  $('#resetConfirmation').modal('toggle');
});

$("#sendAgainLogin").click(function (e) {
  e.preventDefault();
  $('#messages').empty();
  url = "/login/resend";
  var tokenKey = $("meta[name='csrf-param']").attr('content');
  var token = $("meta[name='csrf-token']").attr('content');
  var postData = {
    email: $("#email").val(),
  };
  postData[tokenKey] = token;
  $.ajax({
    url: url,
    type: "post",
    dataType: 'JSON',
    data: postData,
    success: function (data) {
      $("meta[name='csrf-param']").attr('content', data[0].tokenKey);
      $("meta[name='csrf-token']").attr('content', data[0].token);
      if((data.length == 2) && (data[1].result == 'success')) {
        $('#resetConfirmation').modal('toggle');
        setTimeout(function(){$('#resetConfirmation').modal('toggle')}, 600);
      } else {
        for(var i = 1; i < data.length; i++) {
          $('#messages').append("<div class='alert alert-danger' role='alert'>" + data[i].message + "</div>");
        }
        $('#resetConfirmation').modal('toggle');
        $('#messages').show();
      }
    },
    error: function (data) {
      $('#resetConfirmation').modal('toggle');
      $('#messages').append(ERROR_MESSAGE);
      $('#messages').show();
    }
  });
});

$("#newPasswordForm").bind("submit", function (e) {
  e.preventDefault();
  $('#messages').empty();
  if($('#password').val().length < 8) {
    $('#messages').append("<div class='alert alert-danger' role='alert'>Длина пароля должна быть минимум 8 символов!</div>");
    $('#messages').show();
    return;
  }
  if($('#password').val() != $('#confirmPassword').val()) {
    $('#messages').append("<div class='alert alert-danger' role='alert'>Пароли не совпадают!</div>");
    $('#messages').show();
    return;
  }
  url = $("#newPasswordForm").attr("action");
  var tokenKey = $("meta[name='csrf-param']").attr('content');
  var token = $("meta[name='csrf-token']").attr('content');
  var postData = {
    password:  $("#password").val(),
  };
  postData[tokenKey] = token;
  $.ajax({
    url: url,
    type: "post",
    dataType: 'JSON',
    data: postData,
    success: function (data) {
      window.location.href = data[0].redirect;
    },
    error: function (data) {
      $('#messages').append(ERROR_MESSAGE);
      $('#messages').show();
    }
  });
});

$("#contactForm").bind("submit", function (e) {
    e.preventDefault();
    $("#contactFormMessage").empty();
    $("#contactFormMessage").removeClass("alert-danger");
    $("#contactFormMessage").removeClass("alert-success");
    var tokenKey = $("meta[name='csrf-param']").attr('content');
    var token = $("meta[name='csrf-token']").attr('content');
    var postData = {
        email: $("#contactFormEmail").val(),
        name: $("#contactFormName").val(),
        question: $("#contactFormQuestion").val(),
    };
    postData[tokenKey] = token;
    var url = $("#contactForm").attr("action");
    $.ajax({
        url: url,
        type: "post",
        dataType: 'JSON',
        data: postData,
        success: function (data) {
            $("meta[name='csrf-param']").attr('content', data[0].tokenKey);
            $("meta[name='csrf-token']").attr('content', data[0].token);
            if((data.length == 2) && (data[1].result == 'success')) {
                $("#contactFormMessage").removeClass("alert-danger");
                $("#contactFormMessage").addClass("alert-success");
                $("#contactFormMessage").append("<div class='alert alert-success' role='alert'>Спасибо! Мы постараемся ответить как можно скорее!</div>");
                $("#contactForm").trigger("reset");
            } else {
                for(var i = 1; i < data.length; i++) {
                    $('#contactFormMessage').append("<div class='alert alert-danger' role='alert'>" + data[i].message + "</div>");
                }
            }
        },
        error: function (data) {
            $("#contactFormMessage").addClass("alert-danger"),
            $("#contactFormMessage").removeClass("alert-success"),
            $("#contactFormMessage").append(ERROR_MESSAGE);
        },
    });
});

$('#exitBtn').click(function(e) {
  $('#exitModal').modal('show');
});

$('#removeBtn').click(function(e) {
  $('#removeModal').modal('show');
});

$('#changeEmailBtn').click(function(e) {
  $('#changeEmailModal').modal('show');
  $('#changeEmailForm').trigger('reset');
  $('#messages').empty();
});

$('#changePasswordBtn').click(function(e) {
  $('#changePasswordModal').modal('show');
  $('#passwordMessages').empty();
  $("#changePasswordForm").trigger('reset');
});

$('#changeEmailConfirmationBtn').click(function(e) {
  $('#changeEmailForm').submit();
});

$('#exitConfirmationBtn').click(function(e) {
  var tokenKey = $("meta[name='csrf-param']").attr('content');
  var token = $("meta[name='csrf-token']").attr('content');
  var postData = {
  };
  postData[tokenKey] = token;
  var url = '/profile/logout';
  $.ajax({
      url: url,
      type: "post",
      dataType: 'JSON',
      data: postData,
      success: function (data) {
        window.location.href = data[0].redirect;
      },
      error: function (data) {
        alert('Произошла ошибка... Обновите страницу или повторите попытку позднее. Если проблема не исчезнет обратитесь в службу поддержки.');
      },
  });
});

$('#removeConfirmationBtn').click(function(e) {
  var tokenKey = $("meta[name='csrf-param']").attr('content');
  var token = $("meta[name='csrf-token']").attr('content');
  var postData = {
  };
  postData[tokenKey] = token;
  var url = '/profile/remove';
  $.ajax({
      url: url,
      type: "post",
      dataType: 'JSON',
      data: postData,
      success: function (data) {
        window.location.href = data[0].redirect;
      },
      error: function (data) {
        alert('Произошла ошибка... Обновите страницу или повторите попытку позднее. Если проблема не исчезнет обратитесь в службу поддержки.');
      },
  });
});

$("#changeEmailForm").bind("submit", function (e) {
  e.preventDefault();
  $('#messages').empty();
  url = $("#changeEmailForm").attr("action");
  var tokenKey = $("meta[name='csrf-param']").attr('content');
  var token = $("meta[name='csrf-token']").attr('content');
  var postData = {
    email: $("#email").val(),
  };
  postData[tokenKey] = token;
  $.ajax({
    url: url,
    type: "post",
    dataType: 'JSON',
    data: postData,
    success: function (data) {
      $("meta[name='csrf-param']").attr('content', data[0].tokenKey);
      $("meta[name='csrf-token']").attr('content', data[0].token);
      if((data.length == 2) && (data[1].result == 'success')) {
        $('#changeEmailConfirmationModal').modal('toggle');
        $('#changeEmailModal').modal('toggle');
        $('#emailNotification').text(data[1].email);
        $("#changeEmailForm").reset();
      } else {
        for(var i = 1; i < data.length; i++) {
          $('#messages').append("<div class='alert alert-danger' role='alert'>" + data[i].message + "</div>");
        }
        $('#messages').show();
      }
    },
    error: function (data) {
      $('#messages').append(ERROR_MESSAGE);
      $('#messages').show();
    }
  });
});

$("#changePasswordForm").bind("submit", function (e) {
  e.preventDefault();
  $('#passwordMessages').empty();
  if($('#oldPassword').val().length < 8) {
    $('#passwordMessages').append("<div class='alert alert-danger' role='alert'>Длина старого пароля должна быть минимум 8 символов!</div>");
    return;
  }
  if($('#newPassword').val().length < 8) {
    $('#passwordMessages').append("<div class='alert alert-danger' role='alert'>Длина нового пароля должна быть минимум 8 символов!</div>");
    return;
  }
  if($('#confirmPassword').val().length < 8) {
    $('#passwordMessages').append("<div class='alert alert-danger' role='alert'>Длина подтверждения пароля должна быть минимум 8 символов!</div>");
    return;
  }
  if($('#newPassword').val() != $('#confirmPassword').val()) {
    $('#passwordMessages').append("<div class='alert alert-danger' role='alert'>Подтверждение пароля не совпадает с новым паролем!</div>");
    return;
  }
  url = $("#changePasswordForm").attr("action");
  var tokenKey = $("meta[name='csrf-param']").attr('content');
  var token = $("meta[name='csrf-token']").attr('content');
  var postData = {
    oldPassword: $("#oldPassword").val(),
    newPassword: $("#newPassword").val(),
    confirmPassword: $("#confirmPassword").val(),
  };
  postData[tokenKey] = token;
  $.ajax({
    url: url,
    type: "post",
    dataType: 'JSON',
    data: postData,
    success: function (data) {
      $("meta[name='csrf-param']").attr('content', data[0].tokenKey);
      $("meta[name='csrf-token']").attr('content', data[0].token);
      if((data.length == 2) && (data[1].result == 'success')) {
        $('#changePasswordModal').modal('toggle');
        $('#changePasswordSuccessModal').modal('toggle');
        $("#changePasswordForm").reset();
      } else {
        for(var i = 1; i < data.length; i++) {
          $('#passwordMessages').append("<div class='alert alert-danger' role='alert'>" + data[i].message + "</div>");
        }
      }
    },
    error: function (data) {
      $('#passwordMessages').append(ERROR_MESSAGE);
    }
  });
});

$('#createCourseBtn').click(function(e){
  $('#createCourseForm').trigger('reset');
  $('#createCourseModal').modal('toggle');
});

$("#createCourseForm").bind("submit", function (e) {
  e.preventDefault();
  $('#createCourseMessages').empty();
  if($('#password').val().length < 8) {
    $('#createCourseMessages').append("<div class='alert alert-danger' role='alert'>Длина пароля должна быть минимум 8 символов!</div>");
    $('#createCourseMessages').show();
    return;
  }
  if($('#password').val() != $('#confirmPassword').val()) {
    $('#createCourseMessages').append("<div class='alert alert-danger' role='alert'>Пароли не совпадают!</div>");
    $('#createCourseMessages').show();
    return;
  }
  url = $("#createCourseForm").attr("action");
  var tokenKey = $("meta[name='csrf-param']").attr('content');
  var token = $("meta[name='csrf-token']").attr('content');
  var postData = {
    name: $("#name").val(),
    nickname: $("#nickname").val(),
    password:  $("#password").val(),
    confirmPassword: $("#confirmPassword").val()
  };
  postData[tokenKey] = token;
  $.ajax({
    url: url,
    type: "post",
    dataType: 'JSON',
    data: postData,
    success: function (data) {
      $("meta[name='csrf-param']").attr('content', data[0].tokenKey);
      $("meta[name='csrf-token']").attr('content', data[0].token);
      if((data.length == 2) && (data[1].result == 'success')) {
        window.location.href = data[1].redirect;
      } else {
        for(var i = 1; i < data.length; i++) {
          $('#createCourseMessages').append("<div class='alert alert-danger' role='alert'>" + data[i].message + "</div>");
        }
        $('#createCourseMessages').show();
      }
    },
    error: function (data) {
      $('#createCourseMessages').append(ERROR_MESSAGE);
      $('#createCourseMessages').show();
    }
  });
});

$('#joinCourseBtn').click(function(e){
  $('#joinCourseForm').trigger('reset');
  $('#joinCourseModal').modal('toggle');
});

$("#joinCourseForm").bind("submit", function (e) {
  e.preventDefault();
  $('#joinCourseMessages').empty();
  if($('#passwordCourse').val().length < 8) {
    $('#joinCourseMessages').append("<div class='alert alert-danger' role='alert'>Длина пароля должна быть минимум 8 символов!</div>");
    $('#joinCourseMessages').show();
    return;
  }
  if(($.isNumeric($('#code').val()) == false) || $('#code').val() <= 0) {
    $('#joinCourseMessages').append("<div class='alert alert-danger' role='alert'>Код курса - положительное число!</div>");
    $('#joinCourseMessages').show();
    return;
  }
  url = $("#joinCourseForm").attr("action");
  var tokenKey = $("meta[name='csrf-param']").attr('content');
  var token = $("meta[name='csrf-token']").attr('content');
  var postData = {
    code: $("#code").val(),
    passwordCourse:  $("#passwordCourse").val(),
    nickname:  $("#nicknameJoin").val(),
  };
  postData[tokenKey] = token;
  $.ajax({
    url: url,
    type: "post",
    dataType: 'JSON',
    data: postData,
    success: function (data) {
      $("meta[name='csrf-param']").attr('content', data[0].tokenKey);
      $("meta[name='csrf-token']").attr('content', data[0].token);
      if((data.length == 2) && (data[1].result == 'success')) {
        window.location.href = data[1].redirect;
      } else {
        for(var i = 1; i < data.length; i++) {
          $('#joinCourseMessages').append("<div class='alert alert-danger' role='alert'>" + data[i].message + "</div>");
        }
        $('#joinCourseMessages').show();
      }
    },
    error: function (data) {
      $('#joinCourseMessages').append(ERROR_MESSAGE);
      $('#joinCourseMessages').show();
    }
  });
});

$('#changeNicknameBtn').click(function(e) {
  $("#changeNicknameForm").trigger("reset");
  $('#changeNicknameModal').modal('show');
});

$("#changeNicknameForm").bind("submit", function (e) {
  e.preventDefault();
  $('#changeNicknameMessages').empty();
  url = $("#changeNicknameForm").attr("action");
  var tokenKey = $("meta[name='csrf-param']").attr('content');
  var token = $("meta[name='csrf-token']").attr('content');
  var postData = {
    nickname: $("#nickname").val(),
    courseId: $("#courseId").text()
  };
  postData[tokenKey] = token;
  $.ajax({
    url: url,
    type: "post",
    dataType: 'JSON',
    data: postData,
    success: function (data) {
      $("meta[name='csrf-param']").attr('content', data[0].tokenKey);
      $("meta[name='csrf-token']").attr('content', data[0].token);
      if((data.length == 2) && (data[1].result == 'success')) {
        $('#courseNickname').text(data[1].nickname);
        $('#changeNicknameModal').modal('toggle');
        $("#changeNicknameForm").trigger('reset');
      } else {
        for(var i = 1; i < data.length; i++) {
          $('#changeNicknameMessages').append("<div class='alert alert-danger' role='alert'>" + data[i].message + "</div>");
        }
        $('#changeNicknameMessages').show();
      }
    },
    error: function (data) {
      $('#changeNicknameMessages').append(ERROR_MESSAGE);
      $('#changeNicknameMessages').show();
    }
  });
});

$('#leaveCourseBtn').click(function(e) {
  $('#leaveCourseModal').modal('show');
});

$('#leaveCourseConfirmationBtn').click(function(e) {
  $('#settingsTabMessages').empty();
  var tokenKey = $("meta[name='csrf-param']").attr('content');
  var token = $("meta[name='csrf-token']").attr('content');
  var postData = {
    courseId: $("#courseId").text()
  };
  postData[tokenKey] = token;
  var url = '/profile/course/leave';
  $.ajax({
      url: url,
      type: "post",
      dataType: 'JSON',
      data: postData,
      success: function (data) {
        $("meta[name='csrf-param']").attr('content', data[0].tokenKey);
        $("meta[name='csrf-token']").attr('content', data[0].token);
        if((data.length == 2) && (data[1].result == 'success')) {
          location.reload();
        } else {
          for(var i = 1; i < data.length; i++) {
            $('#settingsTabMessages').append("<div class='alert alert-danger' role='alert'>" + data[i].message + "</div>");
          }
          $('#leaveCourseModal').modal('toggle');
        }
      },
      error: function (data) {
        $('#settingsTabMessages').append(ERROR_MESSAGE);
        $('#leaveCourseModal').modal('toggle');
      },
  });
});

$('#changeCourseNameBtn').click(function(e) {
  $('#changeCourseNameForm').trigger('reset');
  $('#changeCourseNameModal').modal('show');
});

$('#changeCourseNameForm').bind('submit', function(e) {
  e.preventDefault();
  $('#changeCourseNameMessages').empty();
  var tokenKey = $("meta[name='csrf-param']").attr('content');
  var token = $("meta[name='csrf-token']").attr('content');
  var postData = {
    courseId: $("#courseId").text(),
    courseName: $("#newCourseName").val()
  };
  postData[tokenKey] = token;
  var url = '/profile/course/changeName';
  $.ajax({
      url: url,
      type: "post",
      dataType: 'JSON',
      data: postData,
      success: function (data) {
        $("meta[name='csrf-param']").attr('content', data[0].tokenKey);
        $("meta[name='csrf-token']").attr('content', data[0].token);
        if((data.length == 2) && (data[1].result == 'success')) {
          $("#changeCourseNameForm").trigger('reset');
          $('#changeCourseNameModal').modal('toggle');
          $('#courseName').text(data[1].courseName);
          document.title = data[1].courseName;
        } else {
          for(var i = 1; i < data.length; i++) {
            $('#changeCourseNameMessages').append("<div class='alert alert-danger' role='alert'>" + data[i].message + "</div>");
          }
        }
      },
      error: function (data) {
        $('#changeCourseNameMessages').append(ERROR_MESSAGE);
      },
  });
});

$('#removeCourseBtn').click(function(e) {
  $('#removeCourseModal').modal('show');
  $('#settingsTabMessages').empty();
});

$('#removeCourseConfirmationBtn').click(function() {
  $('#settingsTabMessages').empty();
  var tokenKey = $("meta[name='csrf-param']").attr('content');
  var token = $("meta[name='csrf-token']").attr('content');
  var postData = {
    courseId: $("#courseId").text(),
  };
  postData[tokenKey] = token;
  $.ajax({
      url: '/profile/course/remove',
      type: "post",
      dataType: 'JSON',
      data: postData,
      success: function (data) {
        $('#removeCourseModal').modal('toggle');
        $("meta[name='csrf-param']").attr('content', data[0].tokenKey);
        $("meta[name='csrf-token']").attr('content', data[0].token);
        if((data.length == 2) && (data[1].result == 'success')) {
          location.reload();
        } else {
          for(var i = 1; i < data.length; i++) {
            $('#settingsTabMessages').append("<div class='alert alert-danger' role='alert'>" + data[i].message + "</div>");
          }
        }
      },
      error: function (data) {
        $('#removeCourseModal').modal('toggle');
        $('#settingsTabMessages').append(ERROR_MESSAGE);
      },
  });
});

$('#changeCoursePasswordForm').bind('submit', function(e) {
  e.preventDefault();
  $('#changeCoursePasswordMessages').empty();
  if($('#newPassword').val().length < 8) {
    $('#changeCoursePasswordMessages').append("<div class='alert alert-danger' role='alert'>Длина нового пароля должна быть минимум 8 символов!</div>");
    return;
  }
  if($('#confirmPassword').val().length < 8) {
    $('#changeCoursePasswordMessages').append("<div class='alert alert-danger' role='alert'>Длина подтверждения пароля должна быть минимум 8 символов!</div>");
    return;
  }
  if($('#newPassword').val() != $('#confirmPassword').val()) {
    $('#changeCoursePasswordMessages').append("<div class='alert alert-danger' role='alert'>Подтверждение пароля не совпадает с новым паролем!</div>");
    return;
  }
  var url = $("#changeCoursePasswordForm").attr("action");
  var tokenKey = $("meta[name='csrf-param']").attr('content');
  var token = $("meta[name='csrf-token']").attr('content');
  var postData = {
    courseId: $("#courseId").text(),
    newPassword: $("#newPassword").val(),
    confirmPassword: $("#confirmPassword").val(),
  };
  postData[tokenKey] = token;
  $.ajax({
      url: url,
      type: "post",
      dataType: 'JSON',
      data: postData,
      success: function (data) {
        $("meta[name='csrf-param']").attr('content', data[0].tokenKey);
        $("meta[name='csrf-token']").attr('content', data[0].token);
        if((data.length == 2) && (data[1].result == 'success')) {
          $('#changeCoursePasswordModal').modal('toggle');
          $('#changePasswordSuccessModal').modal('toggle');
        } else {
          for(var i = 1; i < data.length; i++) {
            $('#changeCoursePasswordMessages').append("<div class='alert alert-danger' role='alert'>" + data[i].message + "</div>");
          }
        }
      },
      error: function (data) {
        $('#changeCoursePasswordMessages').append(ERROR_MESSAGE);
      },
  });
});

$('#changeCoursePasswordBtn').click(function(e) {
  $('#changeCoursePasswordForm').trigger('reset');
  $('#changeCoursePasswordMessages').empty();
  $('#changeCoursePasswordModal').modal('show');
});

$('#removeMemberConfirmationBtn').click(function() {
  var courseuserid = $('#memberIdModal').text();
  $('#membersTabMessages').empty();
  var tokenKey = $("meta[name='csrf-param']").attr('content');
  var token = $("meta[name='csrf-token']").attr('content');
  var postData = {
    courseId: $("#courseId").text(),
    courseUserId: courseuserid,
  };
  postData[tokenKey] = token;
  $.ajax({
      url: '/profile/course/removeMember',
      type: "post",
      dataType: 'JSON',
      data: postData,
      success: function (data) {
        $("meta[name='csrf-param']").attr('content', data[0].tokenKey);
        $("meta[name='csrf-token']").attr('content', data[0].token);
        $('#removeMemberModal').modal('toggle');
        if((data.length == 2) && (data[1].result == 'success')) {
          getMembers();
        } else {
          for(var i = 1; i < data.length; i++) {
            $('#membersTabMessages').append("<div class='alert alert-danger' role='alert'>" + data[i].message + "</div>");
          }
        }
      },
      error: function (data) {
        $('#removeMemberModal').modal('toggle');
        $('#membersTabMessages').append(ERROR_MESSAGE);
      },
  });
});

$('#removeMaterialConfirmationBtn').click(function() {
  var material_id = $('#materialIdModal').text();
  $('#materialsTabMessages').empty();
  var tokenKey = $("meta[name='csrf-param']").attr('content');
  var token = $("meta[name='csrf-token']").attr('content');
  var postData = {
    courseId: $("#courseId").text(),
    materialId: material_id,
  };
  postData[tokenKey] = token;
  $.ajax({
      url: '/profile/course/removeMaterial',
      type: "post",
      dataType: 'JSON',
      data: postData,
      success: function (data) {
        $("meta[name='csrf-param']").attr('content', data[0].tokenKey);
        $("meta[name='csrf-token']").attr('content', data[0].token);
        $('#removeMaterialModal').modal('toggle');
        if((data.length == 2) && (data[1].result == 'success')) {
          getMaterials();
        } else {
          for(var i = 1; i < data.length; i++) {
            $('#materialsTabMessages').append("<div class='alert alert-danger' role='alert'>" + data[i].message + "</div>");
          }
        }
      },
      error: function (data) {
        $('#removeMaterialModal').modal('toggle');
        $('#materialsTabMessages').append(ERROR_MESSAGE);
      },
  });
});

$('#removeTaskConfirmationBtn').click(function() {
  var task_id = $('#taskIdModal').text();
  $('#tasksTabMessages').empty();
  var tokenKey = $("meta[name='csrf-param']").attr('content');
  var token = $("meta[name='csrf-token']").attr('content');
  var postData = {
    courseId: $("#courseId").text(),
    taskId: task_id,
  };
  postData[tokenKey] = token;
  $.ajax({
      url: '/profile/course/removeTask',
      type: "post",
      dataType: 'JSON',
      data: postData,
      success: function (data) {
        $("meta[name='csrf-param']").attr('content', data[0].tokenKey);
        $("meta[name='csrf-token']").attr('content', data[0].token);
        $('#removeTaskModal').modal('toggle');
        if((data.length == 2) && (data[1].result == 'success')) {
          getTasks();
        } else {
          getTasks();
          for(var i = 1; i < data.length; i++) {
            $('#tasksTabMessages').append("<div class='alert alert-danger' role='alert'>" + data[i].message + "</div>");
          }
        }
      },
      error: function (data) {
        $('#removeTaskModal').modal('toggle');
        $('#tasksTabMessages').append(ERROR_MESSAGE);
      },
  });
});

$('#returnUsersFiles').click(function() {
  // var task_id = $('#taskIdModal').text();
  $('#sendTaskFilesErrorMessages').empty();
  var tokenKey = $("meta[name='csrf-param']").attr('content');
  var token = $("meta[name='csrf-token']").attr('content');
  var postData = {
    courseId: $("#courseId").val(),
    taskId: $("#taskId").val(),
  };
  postData[tokenKey] = token;
  $.ajax({
      url: '/profile/course/task/returnUsersFiles',
      type: "post",
      dataType: 'JSON',
      data: postData,
      success: function (data) {
        $("meta[name='csrf-param']").attr('content', data[0].tokenKey);
        $("meta[name='csrf-token']").attr('content', data[0].token);
        $('#removeTaskModal').modal('toggle');
        if((data.length == 2) && (data[1].result == 'success')) {
          location.reload();
          // getTasks();
        } else {
          // getTasks();
          for(var i = 1; i < data.length; i++) {
            $('#sendTaskFilesErrorMessages').append("<div class='alert alert-danger' role='alert'>" + data[i].message + "</div>");
          }
        }
      },
      error: function (data) {
        // $('#removeTaskModal').modal('toggle');
        $('#sendTaskFilesErrorMessages').append(ERROR_MESSAGE);
      },
  });
});

$('#changeMemberRoleConfirmationBtn').click(function() {
  var courseuserid = $('#changeMemberRoleId').text();
  $('#membersTabMessages').empty();
  var tokenKey = $("meta[name='csrf-param']").attr('content');
  var token = $("meta[name='csrf-token']").attr('content');
  var postData = {
    courseId: $("#courseId").text(),
    courseUserId: courseuserid,
    newRole: $("#changeMemberRoleValue").text()
  };
  postData[tokenKey] = token;
  $.ajax({
      url: '/profile/course/changeMemberRole',
      type: "post",
      dataType: 'JSON',
      data: postData,
      success: function (data) {
        $("meta[name='csrf-param']").attr('content', data[0].tokenKey);
        $("meta[name='csrf-token']").attr('content', data[0].token);
        $('#changeMemberRoleModal').modal('toggle');
        if((data.length == 2) && (data[1].result == 'success')) {
          location.reload();
        } else {
          getMembers();
          for(var i = 1; i < data.length; i++) {
            $('#membersTabMessages').append("<div class='alert alert-danger' role='alert'>" + data[i].message + "</div>");
          }
        }
      },
      error: function (data) {
        getMembers();
        $('#changeMemberRoleModal').modal('toggle');
        $('#membersTabMessages').append(ERROR_MESSAGE);
      },
  });
});

$('#changeMemberRoleCancelBtn').click(function() {
  getMembers();
});

$("#addMaterialForm").bind("submit", function (e) {
  e.preventDefault();
  $('#materialsFileErrors').empty();
  $('#materialsTabMessages').empty();
  url = $("#addMaterialForm").attr("action");
  var tokenKey = $("meta[name='csrf-param']").attr('content');
  var token = $("meta[name='csrf-token']").attr('content');
  $('#materialsFormCSRF').attr('name', tokenKey);
  $('#materialsFormCSRF').attr('value', token);
  $('#materialsFormCourseCode').attr('value', $("#courseId").text());
  var files = $('#materialFiles').prop('files');
  for(var i=0; i<files.length; i++) {
    if(files[i].size > 8388608) {
      $('#materialsFileErrors').append("<div class='alert alert-danger' role='alert'>Каждый файл не может превышать 8 Мб!</div>");
      return;
    }
  }
  var $that = $(this);
  var formData = new FormData($that.get(0));
  $.ajax({
    url: url,
    contentType: false,
		processData: false,
    type: "post",
    dataType: 'JSON',
    data: formData,
    beforeSend: function() {
      $('#addMaterialBtn').prop('disabled', true);
    },
    success: function (data) {
      getMaterials();
      $('#addMaterialBtn').prop('disabled', false);
      $("meta[name='csrf-param']").attr('content', data[0].tokenKey);
      $("meta[name='csrf-token']").attr('content', data[0].token);
      if((data.length == 2) && (data[1].result == 'success')) {
        $('#addMaterialForm').trigger('reset');
        editor.setData('');
      } else {
        for(var i = 1; i < data.length; i++) {
          $('#materialsTabMessages').append("<div class='alert alert-danger' role='alert'>" + data[i].message + "</div>");
        }
      }
    },
    error: function (data) {
      getMaterials();
      $('#addMaterialBtn').prop('disabled', false);
      $('#materialsTabMessages').append(ERROR_MESSAGE);
    }
  });
});

$("#addTaskForm").bind("submit", function (e) {
  e.preventDefault();
  $('#tasksFileErrors').empty();
  $('#tasksTabMessages').empty();
  url = $("#addTaskForm").attr("action");
  var tokenKey = $("meta[name='csrf-param']").attr('content');
  var token = $("meta[name='csrf-token']").attr('content');
  $('#tasksFormCSRF').attr('name', tokenKey);
  $('#tasksFormCSRF').attr('value', token);
  $('#tasksFormCourseCode').attr('value', $("#courseId").text());
  var files = $('#taskFiles').prop('files');
  for(var i=0; i<files.length; i++) {
    if(files[i].size > 8388608) {
      $('#tasksFileErrors').append("<div class='alert alert-danger' role='alert'>Каждый файл не может превышать 8 Мб!</div>");
      return;
    }
  }
  var $that = $(this);
  var formData = new FormData($that.get(0));
  $.ajax({
    url: url,
    contentType: false,
		processData: false,
    type: "post",
    dataType: 'JSON',
    data: formData,
    beforeSend: function() {
      $('#addTaskBtn').prop('disabled', true);
    },
    success: function (data) {
      getTasks();
      $('#addTaskBtn').prop('disabled', false);
      $("meta[name='csrf-param']").attr('content', data[0].tokenKey);
      $("meta[name='csrf-token']").attr('content', data[0].token);
      if((data.length == 2) && (data[1].result == 'success')) {
        $('#addTaskForm').trigger('reset');
        editorAddTask.setData('');
      } else {
        for(var i = 1; i < data.length; i++) {
          $('#tasksTabMessages').append("<div class='alert alert-danger' role='alert'>" + data[i].message + "</div>");
        }
      }
    },
    error: function (data) {
      getTasks();
      $('#addTaskBtn').prop('disabled', false);
      $('#tasksTabMessages').append(ERROR_MESSAGE);
    }
  });
});

$("#uploadFilesForTask").bind("submit", function (e) {
  e.preventDefault();
  $('#sendTaskFilesErrorMessages').empty();
  // $('#tasksTabMessages').empty();
  url = $("#uploadFilesForTask").attr("action");
  var tokenKey = $("meta[name='csrf-param']").attr('content');
  var token = $("meta[name='csrf-token']").attr('content');
  $('#tasksFormCSRF').attr('name', tokenKey);
  $('#tasksFormCSRF').attr('value', token);
  // $('#tasksFormCourseCode').attr('value', $("#courseId").text());
  var files = $('#uploadTaskFiles').prop('files');
  for(var i=0; i<files.length; i++) {
    if(files[i].size > 8388608) {
      $('#sendTaskFilesErrorMessages').append("<div class='alert alert-danger' role='alert'>Каждый файл не может превышать 8 Мб!</div>");
      return;
    }
  }
  var $that = $(this);
  var formData = new FormData($that.get(0));
  $.ajax({
    url: url,
    contentType: false,
		processData: false,
    type: "post",
    dataType: 'JSON',
    data: formData,
    beforeSend: function() {
      // $('#addTaskBtn').prop('disabled', true);
    },
    success: function (data) {
      // getTasks();
      // getMessages();
      // $('#addTaskBtn').prop('disabled', false);
      $("meta[name='csrf-param']").attr('content', data[0].tokenKey);
      $("meta[name='csrf-token']").attr('content', data[0].token);
      if((data.length == 2) && (data[1].result == 'success')) {
        // $('#uploadFilesForTask').trigger('reset');
        // editorAddTask.setData('');
        location.reload();
      } else {
        for(var i = 1; i < data.length; i++) {
          $('#sendTaskFilesErrorMessages').append("<div class='alert alert-danger' role='alert'>" + data[i].message + "</div>");
        }
      }
    },
    error: function (data) {
      // getTasks();
      // $('#addTaskBtn').prop('disabled', false);
      $('#sendTaskFilesErrorMessages').append(ERROR_MESSAGE);
    }
  });
});

$("#editMaterialConfirmationBtn").click(function (e) {
  $('#editMaterialForm').submit();
});

$("#editTaskConfirmationBtn").click(function (e) {
  $('#editTaskForm').submit();
});

$("#editMaterialForm").bind('submit', function (e) {
  e.preventDefault();
  $('#editMaterialsFileErrors').empty();
  $('#materialsTabMessages').empty();
  url = $("#editMaterialForm").attr("action");
  var tokenKey = $("meta[name='csrf-param']").attr('content');
  var token = $("meta[name='csrf-token']").attr('content');
  $('#editMaterialsFormCSRF').attr('name', tokenKey);
  $('#editMaterialsFormCSRF').attr('value', token);
  $('#editMaterialsFormCourseCode').attr('value', $("#courseId").text());
  $('#editMaterialDescription').val(editor2.getData());
  var files = $('#editMaterialFiles').prop('files');
  for(var i=0; i<files.length; i++) {
    if(files[i].size > 8388608) {
      $('#editMaterialsFileErrors').append("<div class='alert alert-danger' role='alert'>Каждый файл не может превышать 8 Мб!</div>");
      return;
    }
  }
  var $that = $(this);
  var formData = new FormData($that.get(0));
  $.ajax({
    url: url,
    contentType: false,
		processData: false,
    type: "post",
    dataType: 'JSON',
    data: formData,
    beforeSend: function() {
      $('#editMaterialBtn').prop('disabled', true);
    },
    success: function (data) {
      getMaterials();
      $('#editMaterialModal').modal('toggle');
      $('#editMaterialBtn').prop('disabled', false);
      $("meta[name='csrf-param']").attr('content', data[0].tokenKey);
      $("meta[name='csrf-token']").attr('content', data[0].token);
      if((data.length == 2) && (data[1].result == 'success')) {
      } else {
        for(var i = 1; i < data.length; i++) {
          $('#materialsTabMessages').append("<div class='alert alert-danger' role='alert'>" + data[i].message + "</div>");
        }
      }
    },
    error: function (data) {
      getMaterials();
      $('#editMaterialModal').modal('toggle');
      $('#editMaterialBtn').prop('disabled', false);
      $('#materialsTabMessages').append(ERROR_MESSAGE);
    }
  });
});

$("#editTaskForm").bind('submit', function (e) {
  e.preventDefault();
  $('#editTasksFileErrors').empty();
  $('#tasksTabMessages').empty();
  url = $("#editTaskForm").attr("action");
  var tokenKey = $("meta[name='csrf-param']").attr('content');
  var token = $("meta[name='csrf-token']").attr('content');
  $('#editTasksFormCSRF').attr('name', tokenKey);
  $('#editTasksFormCSRF').attr('value', token);
  $('#editTasksFormCourseCode').attr('value', $("#courseId").text());
  $('#editTaskDescription').val(editorEditTask.getData());
  var files = $('#editTaskFiles').prop('files');
  for(var i=0; i<files.length; i++) {
    if(files[i].size > 8388608) {
      $('#editTasksFileErrors').append("<div class='alert alert-danger' role='alert'>Каждый файл не может превышать 8 Мб!</div>");
      return;
    }
  }
  var $that = $(this);
  var formData = new FormData($that.get(0));
  $.ajax({
    url: url,
    contentType: false,
		processData: false,
    type: "post",
    dataType: 'JSON',
    data: formData,
    beforeSend: function() {
      $('#editTaskBtn').prop('disabled', true);
    },
    success: function (data) {
      getTasks();
      $('#editTaskModal').modal('toggle');
      $('#editTaskBtn').prop('disabled', false);
      $("meta[name='csrf-param']").attr('content', data[0].tokenKey);
      $("meta[name='csrf-token']").attr('content', data[0].token);
      if((data.length == 2) && (data[1].result == 'success')) {
      } else {
        for(var i = 1; i < data.length; i++) {
          $('#tasksTabMessages').append("<div class='alert alert-danger' role='alert'>" + data[i].message + "</div>");
        }
      }
    },
    error: function (data) {
      getTasks();
      $('#editTaskModal').modal('toggle');
      $('#editTaskBtn').prop('disabled', false);
      $('#tasksTabMessages').append(ERROR_MESSAGE);
    }
  });
});

$('#clearMaterialsFiles').click(function(e) {
  e.preventDefault();
  $('#materialFiles').val('');
});

$('#clearTasksFiles').click(function(e) {
  e.preventDefault();
  $('#taskFiles').val('');
});

$('#clearEditMaterialsFiles').click(function(e) {
  e.preventDefault();
  $('#editMaterialFiles').val('');
});

$('#clearEditTasksFiles').click(function(e) {
  e.preventDefault();
  $('#editTaskFiles').val('');
});

$("#sendMessageForm").bind("submit", function (e) {
  e.preventDefault();
  $('#tasksErrorMessages').empty();
  url = $("#sendMessageForm").attr("action");
  var tokenKey = $("meta[name='csrf-param']").attr('content');
  var token = $("meta[name='csrf-token']").attr('content');
  var postData = {
    taskMessage: $("#taskMessage").val(),
    courseId: $("#courseId").val(),
    taskId: $("#taskId").val(),
  };
  postData[tokenKey] = token;
  $.ajax({
    url: url,
    type: "post",
    dataType: 'JSON',
    data: postData,
    success: function (data) {
      $("#taskMessage").val('');
      $("meta[name='csrf-param']").attr('content', data[0].tokenKey);
      $("meta[name='csrf-token']").attr('content', data[0].token);
      if((data.length == 2) && (data[1].result == 'success')) {
        getMessages();
        // $('#signupConfirmation').modal('show');
        // $('#emailNotification').text(data[1].email);
      } else {
        for(var i = 1; i < data.length; i++) {
          $('#tasksErrorMessages').append("<div class='alert alert-danger' role='alert'>" + data[i].message + "</div>");
        }
      }
    },
    error: function (data) {
      $('#tasksErrorMessages').append(ERROR_MESSAGE);
    }
  });
});

$("#sendMessageFormAdmin").bind("submit", function (e) {
  e.preventDefault();
  $('#tasksErrorMessages').empty();
  url = $("#sendMessageFormAdmin").attr("action");
  var tokenKey = $("meta[name='csrf-param']").attr('content');
  var token = $("meta[name='csrf-token']").attr('content');
  var postData = {
    taskMessage: $("#taskMessage").val(),
    courseId: $("#courseId").val(),
    taskId: $("#taskId").val(),
    receptionistId: user_id,
  };
  postData[tokenKey] = token;
  $.ajax({
    url: url,
    type: "post",
    dataType: 'JSON',
    data: postData,
    success: function (data) {
      $("#taskMessage").val('');
      $("meta[name='csrf-param']").attr('content', data[0].tokenKey);
      $("meta[name='csrf-token']").attr('content', data[0].token);
      if((data.length == 2) && (data[1].result == 'success')) {
        getUserInfo();
      } else {
        for(var i = 1; i < data.length; i++) {
          $('#tasksErrorMessages').append("<div class='alert alert-danger' role='alert'>" + data[i].message + "</div>");
        }
      }
    },
    error: function (data) {
      $('#tasksErrorMessages').append(ERROR_MESSAGE);
    }
  });
});

$("#scoreTaskFormAdmin").bind("submit", function (e) {
  e.preventDefault();
  $('#tasksErrorMessages').empty();
  url = $("#scoreTaskFormAdmin").attr("action");
  var tokenKey = $("meta[name='csrf-param']").attr('content');
  var token = $("meta[name='csrf-token']").attr('content');
  var postData = {
    score: $("#taskScore").val(),
    courseId: $("#courseId").val(),
    taskId: $("#taskId").val(),
    userId: user_id,
  };
  postData[tokenKey] = token;
  $.ajax({
    url: url,
    type: "post",
    dataType: 'JSON',
    data: postData,
    success: function (data) {
      $("#taskMessage").val('');
      $("meta[name='csrf-param']").attr('content', data[0].tokenKey);
      $("meta[name='csrf-token']").attr('content', data[0].token);
      if((data.length == 2) && (data[1].result == 'success')) {
        getUserInfo();
      } else {
        for(var i = 1; i < data.length; i++) {
          $('#tasksErrorMessages').append("<div class='alert alert-danger' role='alert'>" + data[i].message + "</div>");
        }
      }
    },
    error: function (data) {
      $('#tasksErrorMessages').append(ERROR_MESSAGE);
    }
  });
});

$("#cancelScoreTaskFormAdmin").bind("submit", function (e) {
  e.preventDefault();
  $('#tasksErrorMessages').empty();
  url = $("#cancelScoreTaskFormAdmin").attr("action");
  var tokenKey = $("meta[name='csrf-param']").attr('content');
  var token = $("meta[name='csrf-token']").attr('content');
  var postData = {
    courseId: $("#courseId").val(),
    taskId: $("#taskId").val(),
    userId: user_id,
  };
  postData[tokenKey] = token;
  $.ajax({
    url: url,
    type: "post",
    dataType: 'JSON',
    data: postData,
    success: function (data) {
      $("#taskMessage").val('');
      $("meta[name='csrf-param']").attr('content', data[0].tokenKey);
      $("meta[name='csrf-token']").attr('content', data[0].token);
      if((data.length == 2) && (data[1].result == 'success')) {
        getUserInfo();
      } else {
        for(var i = 1; i < data.length; i++) {
          $('#tasksErrorMessages').append("<div class='alert alert-danger' role='alert'>" + data[i].message + "</div>");
        }
      }
    },
    error: function (data) {
      $('#tasksErrorMessages').append(ERROR_MESSAGE);
    }
  });
});
