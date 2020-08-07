const searchQuery = document.getElementById("search-query");
const homeInfo = document.getElementById("home-info");
const searchList = document.getElementById("search-list");
const searchBtn = document.getElementById("search-btn");
const closeBtn = document.getElementById("close");
const selectedItem = document.getElementById("selected-item");
const logo = document.getElementById("logo");
const body = document.querySelector("body");
const loader = document.getElementById("loader");
const status = document.getElementById("status");
const category = document.getElementById("category");
const next = document.getElementById("next");
const previous = document.getElementById("previous");
const svgLoading = `<svg xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.0" width="160px" height="20px" viewBox="0 0 128 16" xml:space="preserve"><path fill="#fbdaa3" fill-opacity="0.42" d="M6.4,4.8A3.2,3.2,0,1,1,3.2,8,3.2,3.2,0,0,1,6.4,4.8Zm12.8,0A3.2,3.2,0,1,1,16,8,3.2,3.2,0,0,1,19.2,4.8ZM32,4.8A3.2,3.2,0,1,1,28.8,8,3.2,3.2,0,0,1,32,4.8Zm12.8,0A3.2,3.2,0,1,1,41.6,8,3.2,3.2,0,0,1,44.8,4.8Zm12.8,0A3.2,3.2,0,1,1,54.4,8,3.2,3.2,0,0,1,57.6,4.8Zm12.8,0A3.2,3.2,0,1,1,67.2,8,3.2,3.2,0,0,1,70.4,4.8Zm12.8,0A3.2,3.2,0,1,1,80,8,3.2,3.2,0,0,1,83.2,4.8ZM96,4.8A3.2,3.2,0,1,1,92.8,8,3.2,3.2,0,0,1,96,4.8Zm12.8,0A3.2,3.2,0,1,1,105.6,8,3.2,3.2,0,0,1,108.8,4.8Zm12.8,0A3.2,3.2,0,1,1,118.4,8,3.2,3.2,0,0,1,121.6,4.8Z"/><g><path fill="#f5a623" fill-opacity="1" d="M-42.7,3.84A4.16,4.16,0,0,1-38.54,8a4.16,4.16,0,0,1-4.16,4.16A4.16,4.16,0,0,1-46.86,8,4.16,4.16,0,0,1-42.7,3.84Zm12.8-.64A4.8,4.8,0,0,1-25.1,8a4.8,4.8,0,0,1-4.8,4.8A4.8,4.8,0,0,1-34.7,8,4.8,4.8,0,0,1-29.9,3.2Zm12.8-.64A5.44,5.44,0,0,1-11.66,8a5.44,5.44,0,0,1-5.44,5.44A5.44,5.44,0,0,1-22.54,8,5.44,5.44,0,0,1-17.1,2.56Z"/><animateTransform attributeName="transform" type="translate" values="23 0;36 0;49 0;62 0;74.5 0;87.5 0;100 0;113 0;125.5 0;138.5 0;151.5 0;164.5 0;178 0" calcMode="discrete" dur="1170ms" repeatCount="indefinite"/></g></svg>`;
const maxPost = 8;
var pNo = 1;
var currentType;
var currentValue;

onload();

function onload() {
  updateCategory();
}

function updateCategory() {
  var xhr = new XMLHttpRequest();
  xhr.open("GET", "http://localhost/api/getcat.php", true);

  //Send the proper header information along with the request
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

  xhr.onreadystatechange = function () {
    // Call a function when the state changes.
    if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
      responsedata = JSON.parse(this.responseText);
      responsedata.forEach((element) => {
        category.innerHTML += `<li class='cat-item'>${element}</li>`;
      });
    }
  };
  xhr.send();
}

function uiInteraction(e, value) {
  if (e === "search") {
    if (searchQuery.value) {
      homeInfo.parentElement.style.justifyContent = "flex-start";
      homeInfo.style.display = "none";
      getdata("search", searchQuery.value, value);
    }
  }
  if (e === "getNextPage") {
    getdata(currentType, currentValue, value);
  }

  if (e === "reset") {
    homeInfo.parentElement.style.justifyContent = "center";
    searchList.innerHTML = "";
    selectedItem.innerHTML = " ";
    status.innerHTML = "";
    previous.style.display = "none";
    next.style.display = "none";
  }

  if (e === "navigation") {
    if (value.navLeft == true) {
      previous.style.display = "block";
    } else if (value.navLeft == false) {
      previous.style.display = "none";
    }
    if (value.navRight == true) {
      next.style.display = "block";
    } else if (value.navRight == false) {
      next.style.display = "none";
    }
  }

  if (e === "removeOverlay") {
    document.getElementById("body-overlay").style.display = "none";
  }
  if (e === "getOverlay") {
    document.getElementById("body-overlay").style.display = "block";
  }

  if (e === "catGetData") {
    getdata("catData", value);
  }
}

function getLink(link) {
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "http://localhost/api/api.php", true);

  //Send the proper header information along with the request
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

  xhr.onreadystatechange = function () {
    // Call a function when the state changes.
    if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
      var res = JSON.parse(this.responseText);
      if (document.getElementById("download")) {
        document.getElementById("download").innerHTML = "";
        if (res.length > 1) {
          for (i = 0; i < res.length; i++) {
            if (i == 0) {
              document.getElementById(
                "download"
              ).innerHTML += `<a href="${res[i]}" target="_blank">Direct Download</a>`;
            } else {
              document.getElementById(
                "download"
              ).innerHTML += `<a href="${res[i]}" target="_blank">Torrent Download</a>`;
            }
          }
        } else {
          document.getElementById(
            "download"
          ).innerHTML += `<a href="${res[0]}" target="_blank">Download</a>`;
        }
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

function getdata(type, value, pageNo = 1) {
  currentType = type;
  currentValue = value;
  if (!value == "") {
    uiInteraction("getOverlay");
    if (pageNo > 1) {
      if (type === "search") {
        var temp = `s=${value}&pageNo=${pageNo}`;
      } else if (type === "catData") {
        var temp = `cat=${value}&pageNo=${pageNo}`;
      }
    } else {
      if (type === "search") {
        var temp = `s=${value}`;
      } else if (type === "catData") {
        var temp = `cat=${value}`;
      }
    }
    fetch(`http://localhost/api/getpost.php?${temp}`)
      .then((res) => res.json())
      .catch((err) => console.log(err))
      .then((data) => {
        uiInteraction("navigation", data[data.length - 1]);
        uiInteraction("removeOverlay");
        if (data.length > 1) {
          var temp = "";
          var postCount = 0;
          searchList.innerHTML = "";
          status.innerHTML = `<h2>Showing Result for: <span>${value}</span></h2>`;
          data.forEach((element) => {
            if (postCount < maxPost) {
              postCount++;
              var i = 0;
              var tempCat = "";
              if (element.category) {
                for (i = 0; i < element.category.length; i++) {
                  tempCat += `<span>${element.category[i]}</span>`;
                }
                temp = `
                    <div class="card-1">
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
                searchList.innerHTML += temp;
              }
            }
          });
        } else {
          status.innerHTML = ``;
          searchList.innerHTML = `          
            <div class="no-result">
              <h2>No Result Found for <span>${value}!</span> Try different Keyword</h2>
            </div>`;
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
  if (e.target.classList.contains("cat-item")) {
    pNo = 1;
    uiInteraction("catGetData", e.target.innerText.replace(/\s/g, "-"));
    uiInteraction("getOverlay");
  }
  if (
    e.target.classList.contains("next") ||
    e.target.classList.contains("fa-arrow-square-right")
  ) {
    pNo++;
    uiInteraction("getNextPage", pNo);
  }
  if (
    e.target.classList.contains("previous") ||
    e.target.classList.contains("fa-arrow-square-left")
  ) {
    pNo--;
    uiInteraction("getNextPage", pNo);
  }
});

searchQuery.addEventListener("focusout", () => {
  if (searchQuery.value == "") {
    uiInteraction("reset");
  }
});

searchQuery.addEventListener("keyup", (e) => {
  if (e.keyCode == 13) {
    pNo = 1;
    uiInteraction("search");
  }
});

searchBtn.addEventListener("click", (e) => {
  pNo = 1;
  uiInteraction("search");
});

searchList.addEventListener("click", (e) => {
  if (e.target.classList.contains("get-event")) {
    uiInteraction("getOverlay");

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "http://localhost/api/getpost.php", true);

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
