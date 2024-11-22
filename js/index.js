window.addEventListener("DOMContentLoaded", function (event) {
  console.log("DOM fully loaded and parsed");
  websdkready();
});

function websdkready() {
  var testTool = window.testTool;
  if (testTool.isMobileDevice()) {
    vConsole = new VConsole();
  }
  console.log("checkSystemRequirements");
  console.log(JSON.stringify(ZoomMtg.checkSystemRequirements()));

  // it's option if you want to change the MeetingSDK-Web dependency link resources. setZoomJSLib must be run at first
  // ZoomMtg.setZoomJSLib("https://source.zoom.us/{VERSION}/lib", "/av"); // default, don't need call it
  // ZoomMtg.setZoomJSLib("https://jssdk.zoomus.cn/{VERSION}/lib", "/av"); // china cdn option
  ZoomMtg.preLoadWasm(); // pre download wasm file to save time.

  var CLIENT_ID = "Si9R8RQ5Sx82Azv9okWQ";
  /**
   * NEVER PUT YOUR ACTUAL SDK SECRET OR CLIENT SECRET IN CLIENT SIDE CODE, THIS IS JUST FOR QUICK PROTOTYPING
   * The below generateSignature should be done server side as not to expose your SDK SECRET in public
   * You can find an example in here: https://developers.zoom.us/docs/meeting-sdk/auth/#signature
   */
  var CLIENT_SECRET = "E0QZK0zfhugvjKLtXA6GbsmcqpfyfVKi";

  // some help code, remember mn, pwd, lang ... to cookie, and autofill.
  if(!document.getElementById("display_name").value){
    document.getElementById("display_name").value = testTool.b64DecodeUnicode(testTool.getCookie("display_name"));
  }
  if(!document.getElementById("meeting_number").value){
    document.getElementById("meeting_number").value = testTool.getCookie("meeting_number");
  }
  if(!document.getElementById("meeting_pwd").value){
    document.getElementById("meeting_pwd").value = testTool.getCookie("meeting_pwd");
  }
  if(!document.getElementById("meeting_lang").value){
    document.getElementById("meeting_lang").value = testTool.getCookie("meeting_lang");
  }
  if(!document.getElementById("zoom_user_role").value){
    document.getElementById("meeting_role").innerHTML = "";
    document.getElementById("zoom_user_role").value = testTool.getCookie("zoom_user_role");

    if(testTool.getCookie("zoom_user_role") == "Admin"){
      document.getElementById("meeting_role").appendChild(new Option("Học viên", "0"));
      document.getElementById("meeting_role").appendChild(new Option("Giáo viên", "1"));
    }
    else if(testTool.getCookie("zoom_user_role") == "Teacher"){
      document.getElementById("meeting_role").appendChild(new Option("Giáo viên", "1"));
    }
    else{
      document.getElementById("meeting_role").appendChild(new Option("Học viên", "0"));
    }
  }

  // document.getElementById("display_name").value =
  //   testTool.getCookie("display_name");
  // document.getElementById("meeting_number").value =
  //   testTool.getCookie("meeting_number");
  // document.getElementById("meeting_pwd").value =
  //   testTool.getCookie("meeting_pwd");
  // if (testTool.getCookie("meeting_lang"))
  //   document.getElementById("meeting_lang").value =
  //     testTool.getCookie("meeting_lang");

  document
    .getElementById("meeting_lang")
    .addEventListener("change", function (e) {
      testTool.setCookie(
        "meeting_lang",
        document.getElementById("meeting_lang").value
      );
      testTool.setCookie(
        "_zm_lang",
        document.getElementById("meeting_lang").value
      );
    });
  // copy zoom invite link to mn, autofill mn and pwd.
  document
    .getElementById("meeting_number")
    .addEventListener("input", function (e) {
      var tmpMn = e.target.value.replace(/([^0-9])+/i, "");
      if (tmpMn.match(/([0-9]{9,11})/)) {
        tmpMn = tmpMn.match(/([0-9]{9,11})/)[1];
      }
      var tmpPwd = e.target.value.match(/pwd=([\d,\w]+)/);
      if (tmpPwd) {
        document.getElementById("meeting_pwd").value = tmpPwd[1];
        testTool.setCookie("meeting_pwd", tmpPwd[1]);
      }
      document.getElementById("meeting_number").value = tmpMn;
      testTool.setCookie(
        "meeting_number",
        document.getElementById("meeting_number").value
      );
    });

  document.getElementById("clear_all").addEventListener("click", function (e) {
    testTool.deleteAllCookies();
    document.getElementById("display_name").value = "";
    document.getElementById("meeting_number").value = "";
    document.getElementById("meeting_pwd").value = "";
    document.getElementById("meeting_lang").value = "en-US";
    document.getElementById("meeting_role").value = 0;
    window.location.href = "/index.html";
  });

  // click join meeting button
  document
    .getElementById("join_meeting")
    .addEventListener("click", function (e) {
      e.preventDefault();
      var meetingConfig = testTool.getMeetingConfig();
      if (!meetingConfig.mn || !meetingConfig.name) {
        alert("Meeting number or username is empty");
        return false;
      }

      testTool.setCookie("display_name", meetingConfig.name);
      testTool.setCookie("meeting_role", meetingConfig.role);
      testTool.setCookie("meeting_number", meetingConfig.mn);
      testTool.setCookie("meeting_pwd", meetingConfig.pwd);
      testTool.setCookie("meeting_lang", meetingConfig.lang);
      testTool.setCookie("zoom_user_role", document.getElementById("zoom_user_role").value);
      testTool.setCookie("leave_url", "/mod/ttthzoom/index_iframe.php");

      var signature = ZoomMtg.generateSDKSignature({
        meetingNumber: meetingConfig.mn,
        sdkKey: CLIENT_ID,
        sdkSecret: CLIENT_SECRET,
        role: meetingConfig.role,
        success: function (res) {
          console.log(res);
          meetingConfig.signature = res;
          meetingConfig.sdkKey = CLIENT_ID;
          var joinUrl = "/mod/ttthzoom/meeting_iframe.php?" + testTool.serialize(meetingConfig);
          console.log(joinUrl);
          window.open(joinUrl, "_self");
        },
      });
    });

  function copyToClipboard(elementId) {
    var aux = document.createElement("input");
    aux.setAttribute(
      "value",
      document.getElementById(elementId).getAttribute("link")
    );
    document.body.appendChild(aux);
    aux.select();
    document.execCommand("copy");
    document.body.removeChild(aux);
  }

  // click copy jon link button
  window.copyJoinLink = function (element) {
    var meetingConfig = testTool.getMeetingConfig();
    if (!meetingConfig.mn || !meetingConfig.name) {
      alert("Meeting number or username is empty");
      return false;
    }
    var signature = ZoomMtg.generateSDKSignature({
      meetingNumber: meetingConfig.mn,
      sdkKey: CLIENT_ID,
      sdkSecret: CLIENT_SECRET,
      role: meetingConfig.role,
      success: function (res) {
        console.log(res.result);
        meetingConfig.signature = res.result;
        meetingConfig.sdkKey = CLIENT_ID;
        var joinUrl =
          testTool.getCurrentDomain() +
          "/mod/ttthzoom/meeting_iframe.php?" +
          testTool.serialize(meetingConfig);
        document
          .getElementById("copy_link_value")
          .setAttribute("link", joinUrl);
        copyToClipboard("copy_link_value");
      },
    });
  };
}