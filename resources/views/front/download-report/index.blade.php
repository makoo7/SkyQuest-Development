<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Download PPT</title>
  <script src="https://cdn.jsdelivr.net/gh/gitbrent/pptxgenjs@3.12.0/libs/jszip.min.js"></script>
  <script src="https://cdn.jsdelivr.net/gh/gitbrent/pptxgenjs@3.12.0/dist/pptxgen.min.js"></script>
</head>
<body>

<script>
// Create a new PowerPoint presentation
var pptx = new PptxGenJS();

  // Add a slide with a title and content
var slide = pptx.addSlide();

slide.background = { path: "{{ asset('assets/frontend/slide/1/1.svg') }}" };

slide.addText('Hello, PowerPoint!', { x:1, y:1, fontSize:18, color:'363636' });
slide.addText('This is a slide created using pptxgenjs.', { x:1, y:2, fontSize:14, color:'757575' });

  // Add another slide
var slide2 = pptx.addSlide();
slide2.addText('Second Slide', { x:1, y:1, fontSize:18, color:'363636' });
slide2.addText('Adding more content here.', { x:1, y:2, fontSize:14, color:'757575' });

  // Save the presentation
pptx.writeFile();
</script>
  
</body>
</html>