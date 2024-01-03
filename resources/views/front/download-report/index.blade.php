<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>HTML to PowerPoint</title>
  <script src="https://appsforoffice.microsoft.com/lib/1/hosted/office.js"></script>
  <script src="{{ asset("js/pptxgen.bundle.js") }}"></script>
</head>
<body>
  <!-- Your HTML content here -->
<script>
// Wait for the Office API to be ready
Office.onReady(function (info) {
  if (info.host === Office.HostType.PowerPoint) {
    // Office is ready
    document.getElementById("run").onclick = convertToPPT;
  }
});

function convertToPPT() {
  // Get HTML content from your pages
  const htmlContent = getCombinedHTML(); // Implement your logic to combine HTML pages

  // Convert HTML to PowerPoint
  htmlToPptx(htmlContent, {
    output: "blob",
  }).then((pptxBlob) => {
    // Create PowerPoint document
    Office.context.document.setSelectedDataAsync(pptxBlob, { coercionType: Office.CoercionType.Ooxml }, function (asyncResult) {
      if (asyncResult.status === Office.AsyncResultStatus.Succeeded) {
        console.log("HTML converted to PowerPoint successfully");
      } else {
        console.error("Error converting HTML to PowerPoint", asyncResult.error.message);
      }
    });
  });
}

function getCombinedHTML() {
    alert()
  // Implement your logic to fetch and combine HTML pages
  // For simplicity, let's assume you have a function that fetches HTML content from multiple pages
  const page1 = fetchHTMLPage("page1.html");
  const page2 = fetchHTMLPage("page2.html");
  // Combine HTML content
  const combinedHTML = page1 + page2;
  return combinedHTML;
}

function fetchHTMLPage(pageUrl) {
  // Implement logic to fetch HTML content from a given URL
  // You may use AJAX, Fetch API, or any other method
  // For simplicity, let's assume a synchronous function for fetching HTML
  return "";
//   const xhr = new XMLHttpRequest();
//   xhr.open("GET", pageUrl, false);
//   xhr.send();
//   if (xhr.status === 200) {
//     return xhr.responseText;
//   } else {
//     console.error("Error fetching HTML from " + pageUrl);
//     return "";
//   }
}
</script>
</body>
</html>