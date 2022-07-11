<script>
  // const headEl = document.getElementsByTagName("head")[0];
  // const allLinks = document.getElementsByTagName("link");
  // const faLink = "https://use.fontawesome.com/releases/v5.15.4/css/all.css";

  // if (!Object.keys(allLinks).find((id) => allLinks[id].href === faLink)) {

  //   const linkEl = document.createElement("link");

  //   linkEl.rel = "stylesheet";
  //   linkEl.href = faLink;
  //   linkEl.integrity =
  //     "sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm";
  //   linkEl.crossOrigin = "anonymous";
  //   headEl.appendChild(linkEl);
  //     }

      function editTodaysTasks() {
        const modalPopup = document.createElement('edit-todays-tasks-dialog');
        document.body.appendChild(modalPopup);
        const modal = document.getElementById('modal--contents');
        
      }
</script>

</body>
</html>