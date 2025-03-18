<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Comprehensive Blog Post Example</title>
  <style>
    /* Reset and basic styles */
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #333; line-height: 1.6; }
    a { text-decoration: none; color: inherit; }
    ul { list-style: none; }

    /* Navigation */
    nav {
      background: #333;
      color: #fff;
      padding: 10px 20px;
    }
    nav ul { display: flex; gap: 20px; }
    nav ul li { display: inline; }
    nav a { color: #fff; font-weight: bold; }

    /* Header */
    header {
      text-align: center;
      padding: 40px 20px;
      background: #f4f4f4;
    }
    header h1 { font-size: 2.5em; margin-bottom: 10px; }
    header p { color: #777; margin-bottom: 20px; }
    .main-image { width: 100%; max-height: 400px; object-fit: cover; }

    /* Container */
    .container { max-width: 800px; margin: 20px auto; padding: 0 20px; }

    /* Sections */
    .section { margin-bottom: 40px; }
    .section-heading { margin-bottom: 20px; }
    .section p { margin-bottom: 20px; }

    /* Steps */
    .steps { margin-bottom: 20px; }
    .step {
      border: 1px solid #ddd;
      padding: 10px;
      margin-bottom: 10px;
      border-radius: 5px;
    }
    .step-number {
      background: #007bff;
      color: #fff;
      border-radius: 50%;
      padding: 5px 10px;
      margin-right: 10px;
      display: inline-block;
    }

    /* Quote */
    .quote {
      font-style: italic;
      padding: 10px;
      border-left: 4px solid #007bff;
      margin: 20px 0;
      background: #f9f9f9;
    }

    /* List */
    .list { margin-bottom: 20px; }

    /* Code Block */
    .code {
      background: #f4f4f4;
      padding: 10px;
      border-radius: 5px;
      font-family: monospace;
      margin-bottom: 20px;
    }

    /* Embed */
    .embed { margin-bottom: 20px; }

    /* Gallery */
    .gallery {
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
      margin-bottom: 20px;
    }
    .gallery img {
      width: calc(33.333% - 10px);
      border-radius: 5px;
    }

    /* Custom Block */
    .custom {
      background: #e2f0d9;
      padding: 15px;
      border-left: 4px solid #28a745;
      margin-bottom: 20px;
      border-radius: 5px;
    }

    /* Footer */
    footer {
      background: #333;
      color: #fff;
      text-align: center;
      padding: 20px;
      margin-top: 40px;
    }
  </style>
</head>
<body>
  <!-- Navigation -->
  <nav>
    <ul>
      <li><a href="#">Home</a></li>
      <li><a href="#">Blog</a></li>
      <li><a href="#">About</a></li>
      <li><a href="#">Contact</a></li>
    </ul>
  </nav>

  <!-- Header -->
  <header>
    <h1 id="blog-title"></h1>
    <p id="blog-meta"></p>
    <img id="main-image" class="main-image" src="" alt="">
  </header>

  <!-- Main Content -->
  <div class="container" id="blog-content">
    <!-- Dynamic sections will be inserted here -->
  </div>

  <!-- Footer -->
  <footer>
    <p>&copy; <span id="current-year"></span> My Blog. All rights reserved.</p>
  </footer>

  <script>
    // Static JSON Data
    const blogData = {
      "title": "Comprehensive Blog Post Example",
      "author": "Your Name",
      "publish_date": "2024-01-01T00:00:00Z",
      "main_image": {
        "url": "https://example.com/images/main-banner.jpg",
        "alt": "Main Banner Image"
      },
      "content": [
        {
          "type": "section",
          "heading": {
            "text": "Introduction to Our Blog",
            "level": "h1"
          },
          "content": [
            {
              "type": "paragraph",
              "text": "This is a sample paragraph to showcase textual content."
            },
            {
              "type": "image",
              "url": "https://example.com/images/sample.jpg",
              "alt": "Sample Image"
            },
            {
              "type": "video",
              "url": "https://www.youtube.com/embed/samplevideo"
            },
            {
              "type": "steps",
              "steps": [
                {
                  "number": 1,
                  "title": "Step One",
                  "description": "This is the first step.",
                  "media": {
                    "type": "image",
                    "url": "https://example.com/images/step1.jpg",
                    "alt": "Step One Image"
                  }
                },
                {
                  "number": 2,
                  "title": "Step Two",
                  "description": "This is the second step.",
                  "media": {
                    "type": "video",
                    "url": "https://www.youtube.com/embed/step2video"
                  }
                }
              ]
            },
            {
              "type": "quote",
              "text": "This is a sample testimonial quote.",
              "citation": "John Doe"
            },
            {
              "type": "list",
              "ordered": true,
              "items": [
                "First ordered item",
                "Second ordered item",
                "Third ordered item"
              ]
            },
            {
              "type": "code",
              "language": "javascript",
              "code": "console.log('Hello, world!');"
            },
            {
              "type": "embed",
              "embed_type": "tweet",
              "url": "https://twitter.com/example/status/123456789"
            },
            {
              "type": "gallery",
              "images": [
                {
                  "url": "https://example.com/images/gallery1.jpg",
                  "alt": "Gallery Image 1"
                },
                {
                  "url": "https://example.com/images/gallery2.jpg",
                  "alt": "Gallery Image 2"
                },
                {
                  "url": "https://example.com/images/gallery3.jpg",
                  "alt": "Gallery Image 3"
                }
              ]
            },
            {
              "type": "custom",
              "component": "testimonial",
              "data": {
                "author": "Jane Smith",
                "quote": "This product changed my life!",
                "rating": 5
              }
            }
          ]
        }
      ]
    };

    // Set Blog Header Data
    document.getElementById('blog-title').innerText = blogData.title;
    const publishDate = new Date(blogData.publish_date).toDateString();
    document.getElementById('blog-meta').innerText = `By ${blogData.author} | ${publishDate}`;
    document.getElementById('main-image').src = blogData.main_image.url;
    document.getElementById('main-image').alt = blogData.main_image.alt;
    document.getElementById('current-year').innerText = new Date().getFullYear();

    // Function to render a content block
    function renderContentBlock(block) {
      let element;
      switch (block.type) {
        case 'paragraph':
          element = document.createElement('p');
          element.innerText = block.text;
          break;
        case 'image':
          element = document.createElement('img');
          element.src = block.url;
          element.alt = block.alt;
          break;
        case 'video':
          element = document.createElement('iframe');
          element.src = block.url;
          element.frameBorder = "0";
          element.allow = "accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture";
          element.allowFullscreen = true;
          break;
        case 'steps':
          element = document.createElement('div');
          element.className = 'steps';
          block.steps.forEach(step => {
            const stepDiv = document.createElement('div');
            stepDiv.className = 'step';
            const stepNumber = document.createElement('span');
            stepNumber.className = 'step-number';
            stepNumber.innerText = step.number;
            const stepTitle = document.createElement('strong');
            stepTitle.innerText = step.title;
            const stepDesc = document.createElement('p');
            stepDesc.innerText = step.description;
            stepDiv.appendChild(stepNumber);
            stepDiv.appendChild(stepTitle);
            stepDiv.appendChild(stepDesc);
            if (step.media) {
              stepDiv.appendChild(renderContentBlock(step.media));
            }
            element.appendChild(stepDiv);
          });
          break;
        case 'quote':
          element = document.createElement('blockquote');
          element.className = 'quote';
          element.innerText = block.text;
          if (block.citation) {
            const cite = document.createElement('cite');
            cite.innerText = ` - ${block.citation}`;
            element.appendChild(cite);
          }
          break;
        case 'list':
          element = block.ordered ? document.createElement('ol') : document.createElement('ul');
          block.items.forEach(item => {
            const li = document.createElement('li');
            li.innerText = item;
            element.appendChild(li);
          });
          break;
        case 'code':
          element = document.createElement('pre');
          element.className = 'code';
          const codeElem = document.createElement('code');
          codeElem.innerText = block.code;
          element.appendChild(codeElem);
          break;
        case 'embed':
          element = document.createElement('iframe');
          element.src = block.url;
          element.frameBorder = "0";
          element.allowFullscreen = true;
          break;
        case 'gallery':
          element = document.createElement('div');
          element.className = 'gallery';
          block.images.forEach(img => {
            const imgElem = document.createElement('img');
            imgElem.src = img.url;
            imgElem.alt = img.alt;
            element.appendChild(imgElem);
          });
          break;
        case 'custom':
          element = document.createElement('div');
          element.className = 'custom';
          if (block.component === 'testimonial') {
            element.innerHTML = `<strong>${block.data.author}</strong><p>${block.data.quote}</p><p>Rating: ${block.data.rating} / 5</p>`;
          } else {
            element.innerText = JSON.stringify(block.data);
          }
          break;
        default:
          element = document.createElement('div');
          element.innerText = 'Unknown block type';
      }
      return element;
    }

    // Render each section in the blog content
    const contentContainer = document.getElementById('blog-content');
    blogData.content.forEach(section => {
      if (section.type === 'section') {
        const sectionDiv = document.createElement('div');
        sectionDiv.className = 'section';
        // Render the section heading
        if (section.heading && section.heading.text && section.heading.level) {
          const headingElem = document.createElement(section.heading.level);
          headingElem.innerText = section.heading.text;
          headingElem.className = 'section-heading';
          sectionDiv.appendChild(headingElem);
        }
        // Render each block within the section's content array
        section.content.forEach(block => {
          sectionDiv.appendChild(renderContentBlock(block));
        });
        contentContainer.appendChild(sectionDiv);
      }
    });
  </script>
</body>
</html>
