const form = document.getElementById("form");
const filename = document.getElementById("filename").value;
console.log(filename);
const id = document.getElementById("id").value;
const generated = document.getElementById("generated");
let href;

const data = form.addEventListener("submit", (e) => {
  e.preventDefault();

  var xhr = new XMLHttpRequest();
  xhr.open("POST", "http://localhost/api/api.php", true);

  //Send the proper header information along with the request
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

  xhr.onreadystatechange = function () {
    // Call a function when the state changes.
    if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
      href = this.responseText;
      generated.innerHTML = `<a href="${this.responseText}" id="download" target="_blank">${this.responseText}</a>`;
    }
  };
  xhr.send(`filename=${filename}&id=${id}`);
});
