const searchQuery = document.getElementById("search-query");
const homeInfo = document.getElementById("home-info");
const searchList = document.getElementById("search-list");
const searchBtn = document.getElementById("search-btn");
const closeBtn = document.getElementById("close");
const selectedItem = document.getElementById("selected-item");
const logo = document.getElementById("logo");
const body = document.querySelector("body");
const loader = document.getElementById("loader");
const svgLoading = `<svg xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.0" width="160px" height="20px" viewBox="0 0 128 16" xml:space="preserve"><path fill="#fbdaa3" fill-opacity="0.42" d="M6.4,4.8A3.2,3.2,0,1,1,3.2,8,3.2,3.2,0,0,1,6.4,4.8Zm12.8,0A3.2,3.2,0,1,1,16,8,3.2,3.2,0,0,1,19.2,4.8ZM32,4.8A3.2,3.2,0,1,1,28.8,8,3.2,3.2,0,0,1,32,4.8Zm12.8,0A3.2,3.2,0,1,1,41.6,8,3.2,3.2,0,0,1,44.8,4.8Zm12.8,0A3.2,3.2,0,1,1,54.4,8,3.2,3.2,0,0,1,57.6,4.8Zm12.8,0A3.2,3.2,0,1,1,67.2,8,3.2,3.2,0,0,1,70.4,4.8Zm12.8,0A3.2,3.2,0,1,1,80,8,3.2,3.2,0,0,1,83.2,4.8ZM96,4.8A3.2,3.2,0,1,1,92.8,8,3.2,3.2,0,0,1,96,4.8Zm12.8,0A3.2,3.2,0,1,1,105.6,8,3.2,3.2,0,0,1,108.8,4.8Zm12.8,0A3.2,3.2,0,1,1,118.4,8,3.2,3.2,0,0,1,121.6,4.8Z"/><g><path fill="#f5a623" fill-opacity="1" d="M-42.7,3.84A4.16,4.16,0,0,1-38.54,8a4.16,4.16,0,0,1-4.16,4.16A4.16,4.16,0,0,1-46.86,8,4.16,4.16,0,0,1-42.7,3.84Zm12.8-.64A4.8,4.8,0,0,1-25.1,8a4.8,4.8,0,0,1-4.8,4.8A4.8,4.8,0,0,1-34.7,8,4.8,4.8,0,0,1-29.9,3.2Zm12.8-.64A5.44,5.44,0,0,1-11.66,8a5.44,5.44,0,0,1-5.44,5.44A5.44,5.44,0,0,1-22.54,8,5.44,5.44,0,0,1-17.1,2.56Z"/><animateTransform attributeName="transform" type="translate" values="23 0;36 0;49 0;62 0;74.5 0;87.5 0;100 0;113 0;125.5 0;138.5 0;151.5 0;164.5 0;178 0" calcMode="discrete" dur="1170ms" repeatCount="indefinite"/></g></svg>`;
const maxPost = 8;

function uiInteraction(e) {
  if (e === "update") {
    homeInfo.parentElement.style.justifyContent = "flex-start";
    getdata(searchQuery.value);
  }

  if (e === "reset") {
    homeInfo.parentElement.style.justifyContent = "center";
    searchList.innerHTML = "";
    selectedItem.innerHTML = " ";
  }

  if (e === "removeOverlay") {
    document.getElementById("body-overlay").style.display = "none";
  }
  if (e === "getOverlay") {
    document.getElementById("body-overlay").style.display = "block";
  }
}

function getLink(link) {
  var xhr = new XMLHttpRequest();
  xhr.open(
    "POST",
    "https://testtravelhorse.000webhostapp.com/api/api.php",
    true
  );

  //Send the proper header information along with the request
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

  xhr.onreadystatechange = function () {
    // Call a function when the state changes.
    if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
      var res = JSON.parse(this.responseText);
      if (document.getElementById("download")) {
        document.getElementById(
          "download"
        ).innerHTML = `<a href="${res.link}" target="_blank">Download</a>`;
      }
    }
  };
  xhr.send(`link=${link}`);
}

function getPost(e, link) {
  var temp = `        
  <div class="selected-wrapper">
  <h2>${e.title}</h2>
  <div class="item-wrapper">
    <div class="img-wrapper">
      <img src="${e.img}" />
    </div>
    <div class="item-info">
      <h3>Overview</h3>
      <p>
        ${e.gameOverview}
      </p>
      <h3>Technical Specifications</h3>
      <ul>
        ${e.technicalSpecs}
      </ul>
    </div>
  </div>
  <div id="download" class="download-wrapper">
  ${svgLoading}
  </div>
  <div class="close">
    <i id="close" class="fal fa-times"></i>
  </div>
</div>`;

  loader.style.display = "none";
  selectedItem.innerHTML = temp;

  getLink(link);
}

function getdata(s) {
  if (!s == "") {
    fetch(`https://testtravelhorse.000webhostapp.com/api/getpost.php?s=${s}`)
      .then((res) => res.json())
      .catch((err) => console.log(err))
      .then((data) => {
        if (data.length > 0) {
          var temp = "";
          var postCount = 0;
          searchList.innerHTML = "";
          // console.log(data);
          data.forEach((element) => {
            if (postCount < maxPost) {
              postCount++;
              var i = 0;
              var tempCat = "";
              for (i = 0; i < element.category.length; i++) {
                tempCat += `<span>${element.category[i]}</span>`;
              }
              temp = `<div class="card-1">
                  <div><img src="${element.img}" /></div>
                <div class="post-content">
                  <h2>
                    ${element.title}
                    <div class="cat">
                      ${tempCat}
                    </div>
                  </h2>
                  <div class="post-info">
                    <span>${element.uploadDate}</span>
                  </div>
                </div>
                <div class="get-event" value="${element.href}"></div>
              </div>`;
              uiInteraction("removeOverlay");
              searchList.innerHTML += temp;
            }
          });
        } else {
          console.log(`${data.length} result found`);
        }
      });
  }
}

// Event Listeners

logo.addEventListener("click", () => {
  uiInteraction("reset");
});

body.addEventListener("click", (e) => {
  if (e.target.classList.contains("fa-times")) {
    selectedItem.innerHTML = " ";
    loader.style.display = "block";
    uiInteraction("removeOverlay");
  }
});

searchQuery.addEventListener("focusout", () => {
  if (searchQuery.value == "") {
    uiInteraction("reset");
  }
});

searchQuery.addEventListener("keyup", (e) => {
  if (e.keyCode == 13) {
    uiInteraction("update");
    uiInteraction("getOverlay");
  }
});

searchBtn.addEventListener("click", (e) => {
  uiInteraction("update");
  uiInteraction("getOverlay");
});

searchList.addEventListener("click", (e) => {
  if (e.target.classList.contains("get-event")) {
    // console.log(e.target.getAttribute("value"));

    // selectedItem.innerHTML = svgLoading;

    uiInteraction("getOverlay");

    var xhr = new XMLHttpRequest();
    xhr.open(
      "POST",
      "https://testtravelhorse.000webhostapp.com/api/getpost.php",
      true
    );

    //Send the proper header information along with the request
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
      // Call a function when the state changes.
      if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
        responsedata = JSON.parse(this.responseText);
        getPost(responsedata, e.target.getAttribute("value"));
      }
    };
    xhr.send(`link=${e.target.getAttribute("value")}`);
  }
});
