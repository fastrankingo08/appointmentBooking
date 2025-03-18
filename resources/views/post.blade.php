<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Ultra Pro Blog Post Editor & Live Preview</title>
        <!-- SEO Meta Tags & Structured Data -->
        <meta
            name="description"
            content="An ultra-modern blog post editor with live preview and advanced UX. Review and submit your posts seamlessly."
        />
        <link rel="canonical" href="https://yourdomain.com/new-post" />
        <script type="application/ld+json">
            {
                "@context": "https://schema.org",
                "@type": "BlogPosting",
                "headline": "My Comprehensive Blog Post",
                "datePublished": "2024-05-01",
                "author": {
                    "@type": "Person",
                    "name": "John Doe"
                },
                "image": "https://example.com/images/main-banner.jpg",
                "description": "An ultra-modern blog post editor with live preview and advanced UX."
            }
        </script>
        <style>
            :root {
                --primary-color: #007bff;
                --secondary-color: #0056b3;
                --accent-color: #28a745;
                --bg-color: #f9f9f9;
                --card-bg: #fff;
                --border-color: #ddd;
                --transition-speed: 0.3s;
                --font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            }

            * {
                box-sizing: border-box;
            }
            body {
                margin: 0;
                font-family: var(--font-family);
                background: #eaeaea;
                color: #333;
            }
            header,
            nav,
            footer {
                background: var(--primary-color);
                color: #fff;
                padding: 10px 20px;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            }
            nav ul {
                list-style: none;
                display: flex;
                gap: 20px;
                justify-content: center;
            }
            nav a {
                color: #fff;
                text-decoration: none;
                font-weight: bold;
                transition: color var(--transition-speed);
            }
            nav a:hover {
                color: var(--accent-color);
            }
            footer {
                text-align: center;
                font-size: 0.9em;
            }

            .container {
                max-width: 1000px;
                margin: 30px auto;
                padding: 20px;
            }

            .editor,
            .preview {
                background: var(--card-bg);
                border: 1px solid var(--border-color);
                border-radius: 8px;
                padding: 20px;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
                margin-bottom: 30px;
                animation: fadeIn 0.5s ease-in-out;
            }
            .editor {
                background: var(--bg-color);
            }
            .preview {
                background: var(--card-bg);
            }

            .form-group {
                margin-bottom: 15px;
            }
            .form-group label {
                display: block;
                margin-bottom: 5px;
                font-weight: bold;
            }
            .form-group input,
            .form-group select,
            .form-group textarea {
                width: 100%;
                padding: 10px;
                border: 1px solid var(--border-color);
                border-radius: 4px;
                transition: border-color var(--transition-speed);
            }
            .form-group input:focus,
            .form-group select:focus,
            .form-group textarea:focus {
                border-color: var(--primary-color);
                outline: none;
            }

            button {
                padding: 10px 20px;
                background: var(--primary-color);
                border: none;
                color: #fff;
                border-radius: 4px;
                cursor: pointer;
                transition: background var(--transition-speed),
                    transform var(--transition-speed);
            }
            button:hover {
                background: var(--secondary-color);
                transform: translateY(-2px);
            }

            /* Editor Card Headers */
            .editor h2,
            .preview h2 {
                margin-top: 0;
                text-align: center;
                color: var(--primary-color);
            }

            /* Preview Styling */
            .blog-header {
                text-align: center;
                margin-bottom: 20px;
            }
            .blog-header img {
                width: 100%;
                max-height: 300px;
                object-fit: cover;
                border-radius: 8px;
                margin-top: 10px;
            }
            .section {
                margin-bottom: 30px;
            }
            .section-heading {
                margin-bottom: 15px;
                color: var(--primary-color);
            }
            .paragraph {
                margin-bottom: 15px;
                line-height: 1.6;
            }
            .internal-link {
                color: var(--primary-color);
                text-decoration: underline;
            }
            .steps {
                margin-bottom: 15px;
            }
            .step {
                border: 1px solid var(--border-color);
                padding: 10px;
                margin-bottom: 10px;
                border-radius: 5px;
                background: #fdfdfd;
                transition: box-shadow var(--transition-speed);
            }
            .step:hover {
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            }
            .step-number {
                background: var(--primary-color);
                color: #fff;
                border-radius: 50%;
                padding: 5px 10px;
                margin-right: 10px;
            }
            blockquote {
                font-style: italic;
                background: #f4f4f4;
                padding: 15px;
                border-left: 4px solid var(--primary-color);
                margin: 15px 0;
            }
            .list {
                margin-bottom: 15px;
            }
            .code {
                background: #333;
                color: #fff;
                padding: 15px;
                border-radius: 5px;
                font-family: monospace;
                margin-bottom: 15px;
                overflow-x: auto;
            }
            .gallery {
                display: flex;
                gap: 10px;
                flex-wrap: wrap;
                margin-bottom: 15px;
            }
            .gallery img {
                width: calc(33.333% - 10px);
                border-radius: 5px;
            }
            .custom {
                background: #e2f0d9;
                padding: 15px;
                border-left: 4px solid var(--accent-color);
                margin-bottom: 15px;
                border-radius: 5px;
            }

            /* Section & Block Form styling */
            .section-form,
            .block-form {
                border: 1px solid var(--border-color);
                padding: 15px;
                margin-bottom: 15px;
                background: var(--card-bg);
                border-radius: 6px;
            }
            .section-form h3,
            .block-form h4 {
                margin: 5px 0;
                color: var(--primary-color);
            }
            .block-list {
                margin-left: 20px;
            }

            /* Step form styling */
            .step-form {
                border: 1px dashed var(--border-color);
                padding: 10px;
                margin-bottom: 10px;
                border-radius: 4px;
                background: #fdfdfd;
            }
            .step-form .form-group {
                margin-bottom: 10px;
            }

            /* Confirmation section styling */
            /* Confirmation section styling */
            #confirmation-section {
                border: 1px solid var(--border-color);
                background: linear-gradient(135deg, #f0f8ff, #e0ffff);
                padding: 20px;
                margin: 20px 0;
                border-radius: 8px;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            }

            #confirmation-section .confirm-row {
                display: flex;
                align-items: center;
                gap: 10px;
                margin-bottom: 10px;
            }

            /* Ensure the text input occupies remaining space in its row */
            #confirmation-section .confirm-row input[type="text"] {
                flex: 1;
                border: 1px solid var(--border-color);
                padding: 10px;
                border-radius: 4px;
                transition: border-color var(--transition-speed);
            }
            #confirmation-section .confirm-row input[type="text"]:focus {
                border-color: var(--primary-color);
            }

            #confirmation-section .form-group {
                display: flex;
                align-items: center;
                margin: 5px 10px 5px 0;
            }
            #confirmation-section label {
                margin: 0;
                font-weight: bold;
            }
            #confirmation-section input[type="checkbox"] {
                transform: scale(1.3);
                margin-right: 5px;
                accent-color: var(--primary-color);
            }
            #confirmation-section input[type="text"] {
                border: 1px solid var(--border-color);
                padding: 10px;
                border-radius: 4px;
                transition: border-color var(--transition-speed);
                flex: 1;
            }
            #confirmation-section input[type="text"]:focus {
                border-color: var(--primary-color);
            }

            /* Submitted JSON display */
            #submitted-data {
                background: #f0f0f0;
                padding: 15px;
                margin-top: 20px;
                border: 1px solid var(--border-color);
                font-family: monospace;
                white-space: pre-wrap;
                border-radius: 4px;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(10px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        </style>
    </head>
    <body>
        <!-- Navigation -->
        <nav>
            <ul>
                <li><a href="#">Home</a></li>
                <li><a href="#">New Post</a></li>
                <li><a href="#">My Posts</a></li>
                <li><a href="#">Profile</a></li>
            </ul>
        </nav>

        <!-- Main Container -->
        <div class="container">
            <!-- Editor Panel -->
            <div class="editor">
                <h2>Blog Post Editor</h2>
                <!-- Global Blog Metadata -->
                <div class="form-group">
                    <label for="global-title">Title</label>
                    <input
                        type="text"
                        id="global-title"
                        value="My Comprehensive Blog Post"
                    />
                </div>
                <div class="form-group">
                    <label for="global-author">Author</label>
                    <input type="text" id="global-author" value="John Doe" />
                </div>

                <div class="form-group">
                    <label for="global-date">Publish Date</label>
                    <input type="date" id="global-date" value="2024-05-01" />
                </div>

                <div class="form-group">
                    <label for="global-main-image-url">Main Image URL</label>
                    <input
                        type="text"
                        id="global-main-image-url"
                        value="https://example.com/images/main-banner.jpg"
                    />
                </div>

                <div class="form-group">
                    <label for="global-main-image-alt"
                        >Main Image Alt Text</label
                    >
                    <input
                        type="text"
                        id="global-main-image-alt"
                        value="Main Banner Image"
                    />
                </div>

                
                <hr />
                <h2>Sections</h2>
                <div id="sections-container">
                    <!-- Sections will be added here -->
                </div>
                <button id="add-section">Add Section</button>
                <hr />
                <button id="generate-preview">Generate Preview</button>

                <!-- Confirmation Section -->
                <div id="confirmation-section">
                    <div class="confirm-row">
                        <input type="checkbox" id="reviewed-checkbox" />
                        <label for="reviewed-checkbox"
                            >I have reviewed my full preview.</label
                        >
                    </div>
                    <div class="confirm-row">
                        <input type="checkbox" id="confirm-checkbox" />
                        <label for="confirm-checkbox"
                            >I confirm I want to submit this blog post.</label
                        >
                    </div>
                    <div class="confirm-row">
                        <label for="confirm-text"
                            >Type CONFIRM to finalize submission:</label
                        >
                        <input
                            type="text"
                            id="confirm-text"
                            placeholder="Type CONFIRM here"
                        />
                    </div>
                </div>

                <button id="submit-post">Submit Post</button>
                <!-- Submitted Data Display -->
                <div id="submitted-data" style="display: none"></div>
            </div>

            <!-- Preview Panel (shows below editor) -->
            <div class="preview">
                <h2>Live Preview</h2>
                <div id="preview-container">
                    <!-- Live preview will render here -->
                </div>
            </div>
        </div>

        <footer>
            <p>
                &copy; <span id="current-year"></span> My Blog. All rights
                reserved.
            </p>
        </footer>

        <script>
            // Update footer year
            document.getElementById("current-year").innerText =
                new Date().getFullYear();

            // Global array to store sections (each section will have its own content blocks)
            const sections = [];

            // Function to create a new section form
            function createSectionForm() {
                const sectionIndex = sections.length;
                const sectionData = {
                    heading: { text: "", level: "h2" },
                    content: [],
                };
                sections.push(sectionData);

                // Create DOM elements for section form
                const sectionDiv = document.createElement("div");
                sectionDiv.className = "section-form";
                sectionDiv.dataset.index = sectionIndex;

                sectionDiv.innerHTML = `
        <h3>Section ${sectionIndex + 1}</h3>
        <div class="form-group">
          <label>Heading Text</label>
          <input type="text" class="section-heading-text" placeholder="Section heading" />
        </div>
        <div class="form-group">
          <label>Heading Level</label>
          <select class="section-heading-level">
            <option value="h1">h1</option>
            <option value="h2" selected>h2</option>
            <option value="h3">h3</option>
            <option value="h4">h4</option>
            <option value="h5">h5</option>
            <option value="h6">h6</option>
          </select>
        </div>
        <div class="block-list"></div>
        <button class="add-block">Add Content Block</button>
      `;

                // Add event listener for "Add Content Block" button
                sectionDiv
                    .querySelector(".add-block")
                    .addEventListener("click", function () {
                        addBlockToSection(sectionDiv);
                    });

                document
                    .getElementById("sections-container")
                    .appendChild(sectionDiv);
            }

            // Function to add a new content block to a section form
            function addBlockToSection(sectionDiv) {
                const sectionIndex = sectionDiv.dataset.index;
                const blockContainer = sectionDiv.querySelector(".block-list");
                const blockIndex = blockContainer.children.length;

                const blockDiv = document.createElement("div");
                blockDiv.className = "block-form";
                blockDiv.dataset.blockIndex = blockIndex;

                // Block type selection and container for type-specific fields
                blockDiv.innerHTML = `
        <h4>Content Block ${parseInt(blockIndex) + 1}</h4>
        <div class="form-group">
          <label>Block Type</label>
          <select class="block-type">
            <option value="paragraph">Paragraph</option>
            <option value="image">Image</option>
            <option value="video">Video</option>
            <option value="steps">Steps</option>
            <option value="quote">Quote</option>
            <option value="list">List</option>
            <option value="code">Code</option>
            <option value="embed">Embed</option>
            <option value="gallery">Gallery</option>
            <option value="custom">Custom</option>
            <option value="link">Link (Block-level)</option>
          </select>
        </div>
        <div class="block-fields"></div>
      `;

                // Listen for changes in block type to render corresponding fields
                blockDiv
                    .querySelector(".block-type")
                    .addEventListener("change", function () {
                        renderBlockFields(blockDiv);
                    });

                // Render initial fields (for paragraph by default)
                renderBlockFields(blockDiv);
                blockContainer.appendChild(blockDiv);
            }

            // Function to render type-specific fields in a content block form
            function renderBlockFields(blockDiv) {
                const type = blockDiv.querySelector(".block-type").value;
                const container = blockDiv.querySelector(".block-fields");
                container.innerHTML = ""; // Clear existing fields

                if (type === "paragraph") {
                    container.innerHTML = `
          <div class="form-group">
            <label>Paragraph Text</label>
            <textarea class="block-paragraph" placeholder="Enter paragraph text..."></textarea>
          </div>
          <p style="font-size:0.9em;color:#555;">Tip: You can later convert parts of the text to links using an inline editor.</p>
        `;
                } else if (type === "image") {
                    container.innerHTML = `
          <div class="form-group">
            <label>Image URL</label>
            <input type="text" class="block-url" placeholder="Image URL" />
          </div>
          <div class="form-group">
            <label>Alt Text</label>
            <input type="text" class="block-alt" placeholder="Alt text" />
          </div>
        `;
                } else if (type === "video") {
                    container.innerHTML = `
          <div class="form-group">
            <label>Video URL (Embed URL)</label>
            <input type="text" class="block-url" placeholder="Video embed URL" />
          </div>
        `;
                } else if (type === "steps") {
                    container.innerHTML = `
          <div class="steps-container"></div>
          <button class="add-step">Add Step</button>
        `;
                    container
                        .querySelector(".add-step")
                        .addEventListener("click", function () {
                            addStepToBlock(container);
                        });
                } else if (type === "quote") {
                    container.innerHTML = `
          <div class="form-group">
            <label>Quote Text</label>
            <textarea class="block-text" placeholder="Quote text"></textarea>
          </div>
          <div class="form-group">
            <label>Citation</label>
            <input type="text" class="block-citation" placeholder="Citation" />
          </div>
        `;
                } else if (type === "list") {
                    container.innerHTML = `
          <div class="form-group">
            <label>Ordered?</label>
            <select class="block-ordered">
              <option value="false" selected>No</option>
              <option value="true">Yes</option>
            </select>
          </div>
          <div class="form-group">
            <label>List Items (one per line)</label>
            <textarea class="block-list-items" placeholder="Item 1&#10;Item 2&#10;Item 3"></textarea>
          </div>
        `;
                } else if (type === "code") {
                    container.innerHTML = `
          <div class="form-group">
            <label>Language</label>
            <input type="text" class="block-language" placeholder="e.g., javascript, java" />
          </div>
          <div class="form-group">
            <label>Code</label>
            <textarea class="block-code" placeholder="Your code here"></textarea>
          </div>
        `;
                } else if (type === "embed") {
                    container.innerHTML = `
          <div class="form-group">
            <label>Embed Type (e.g., tweet)</label>
            <input type="text" class="block-embed-type" placeholder="Embed type" />
          </div>
          <div class="form-group">
            <label>Embed URL</label>
            <input type="text" class="block-url" placeholder="Embed URL" />
          </div>
        `;
                } else if (type === "gallery") {
                    container.innerHTML = `
          <div class="form-group">
            <label>Gallery Images (one per line, format: URL|Alt text)</label>
            <textarea class="block-gallery" placeholder="https://example.com/img1.jpg|Alt 1&#10;https://example.com/img2.jpg|Alt 2"></textarea>
          </div>
        `;
                } else if (type === "custom") {
                    container.innerHTML = `
          <div class="form-group">
            <label>Component Name</label>
            <input type="text" class="block-component" placeholder="e.g., testimonial" />
          </div>
          <div class="form-group">
            <label>Component Data (as key=value pairs, one per line)</label>
            <textarea class="block-custom-data" placeholder="author=Jane Doe&#10;quote=Great blog!&#10;rating=5"></textarea>
          </div>
        `;
                } else if (type === "link") {
                    container.innerHTML = `
          <div class="form-group">
            <label>Link URL</label>
            <input type="text" class="block-url" placeholder="/about" />
          </div>
          <div class="form-group">
            <label>Link Text</label>
            <input type="text" class="block-text" placeholder="Learn more" />
          </div>
        `;
                }
            }

            // Function to add a new step form inside a steps block
            function addStepToBlock(stepsContainer) {
                const stepDiv = document.createElement("div");
                stepDiv.className = "step-form";
                stepDiv.innerHTML = `
        <div class="form-group">
          <label>Step Title</label>
          <input type="text" class="step-title" placeholder="Step title" />
        </div>
        <div class="form-group">
          <label>Step Description</label>
          <textarea class="step-description" placeholder="Step description"></textarea>
        </div>
        <div class="form-group">
          <label>Media Type</label>
          <select class="step-media-type">
            <option value="">None</option>
            <option value="image">Image</option>
            <option value="video">Video</option>
          </select>
        </div>
        <div class="form-group step-media-fields" style="display: none;">
          <label>Media URL</label>
          <input type="text" class="step-media-url" placeholder="Media URL" />
          <div class="step-media-alt-container" style="display: none;">
            <label>Alt Text</label>
            <input type="text" class="step-media-alt" placeholder="Alt text" />
          </div>
        </div>
        <button class="remove-step">Remove Step</button>
      `;

                // Toggle media fields based on media type selection
                const mediaTypeSelect =
                    stepDiv.querySelector(".step-media-type");
                mediaTypeSelect.addEventListener("change", function () {
                    const mediaFields =
                        stepDiv.querySelector(".step-media-fields");
                    const altContainer = stepDiv.querySelector(
                        ".step-media-alt-container"
                    );
                    if (mediaTypeSelect.value) {
                        mediaFields.style.display = "block";
                        if (mediaTypeSelect.value === "image") {
                            altContainer.style.display = "block";
                        } else {
                            altContainer.style.display = "none";
                        }
                    } else {
                        mediaFields.style.display = "none";
                    }
                });

                // Remove step event
                stepDiv
                    .querySelector(".remove-step")
                    .addEventListener("click", function () {
                        stepDiv.remove();
                    });

                stepsContainer.appendChild(stepDiv);
            }

            // Event handler for "Add Section" button
            document
                .getElementById("add-section")
                .addEventListener("click", createSectionForm);

            // Function to collect all data from the editor into a JSON object
            function buildBlogJSON() {
                const blogPost = {
                    title: document.getElementById("global-title").value,
                    author: document.getElementById("global-author").value,
                    publish_date: document.getElementById("global-date").value,
                    main_image: {
                        url: document.getElementById("global-main-image-url")
                            .value,
                        alt: document.getElementById("global-main-image-alt")
                            .value,
                    },
                    content: [],
                };

                // Iterate over each section form
                const sectionForms = document.querySelectorAll(".section-form");
                sectionForms.forEach((sectionDiv) => {
                    const headingText = sectionDiv.querySelector(
                        ".section-heading-text"
                    ).value;
                    const headingLevel = sectionDiv.querySelector(
                        ".section-heading-level"
                    ).value;
                    const sectionObj = {
                        type: "section",
                        heading: { text: headingText, level: headingLevel },
                        content: [],
                    };
                    // Iterate over each block in this section
                    const blockForms =
                        sectionDiv.querySelectorAll(".block-form");
                    blockForms.forEach((blockDiv) => {
                        const blockType =
                            blockDiv.querySelector(".block-type").value;
                        let blockObj = { type: blockType };
                        if (blockType === "paragraph") {
                            blockObj.text =
                                blockDiv.querySelector(
                                    ".block-paragraph"
                                ).value;
                        } else if (
                            blockType === "image" ||
                            blockType === "video" ||
                            blockType === "embed" ||
                            blockType === "link"
                        ) {
                            blockObj.url =
                                blockDiv.querySelector(".block-url").value;
                            if (blockType === "image") {
                                blockObj.alt =
                                    blockDiv.querySelector(".block-alt").value;
                            }
                            if (blockType === "embed") {
                                blockObj.embed_type =
                                    blockDiv.querySelector(
                                        ".block-embed-type"
                                    ).value;
                            }
                            if (blockType === "link") {
                                blockObj.text =
                                    blockDiv.querySelector(".block-text").value;
                            }
                        } else if (blockType === "steps") {
                            blockObj.steps = [];
                            const stepForms =
                                blockDiv.querySelectorAll(".step-form");
                            let stepNumber = 1;
                            stepForms.forEach((stepDiv) => {
                                const title =
                                    stepDiv.querySelector(".step-title").value;
                                const description =
                                    stepDiv.querySelector(
                                        ".step-description"
                                    ).value;
                                const mediaType =
                                    stepDiv.querySelector(
                                        ".step-media-type"
                                    ).value;
                                const stepData = {
                                    number: stepNumber,
                                    title: title,
                                    description: description,
                                };
                                if (mediaType) {
                                    stepData.media = {
                                        type: mediaType,
                                        url: stepDiv.querySelector(
                                            ".step-media-url"
                                        ).value,
                                    };
                                    if (mediaType === "image") {
                                        stepData.media.alt =
                                            stepDiv.querySelector(
                                                ".step-media-alt"
                                            ).value;
                                    }
                                }
                                blockObj.steps.push(stepData);
                                stepNumber++;
                            });
                        } else if (blockType === "quote") {
                            blockObj.text =
                                blockDiv.querySelector(".block-text").value;
                            blockObj.citation =
                                blockDiv.querySelector(".block-citation").value;
                        } else if (blockType === "list") {
                            blockObj.ordered =
                                blockDiv.querySelector(".block-ordered")
                                    .value === "true";
                            blockObj.items = blockDiv
                                .querySelector(".block-list-items")
                                .value.split("\n")
                                .filter((item) => item.trim() !== "");
                        } else if (blockType === "code") {
                            blockObj.code =
                                blockDiv.querySelector(".block-code").value;
                            blockObj.language =
                                blockDiv.querySelector(".block-language").value;
                        } else if (blockType === "gallery") {
                            blockObj.images = blockDiv
                                .querySelector(".block-gallery")
                                .value.split("\n")
                                .map((line) => {
                                    const parts = line.split("|");
                                    return {
                                        url: parts[0].trim(),
                                        alt: parts[1] ? parts[1].trim() : "",
                                    };
                                });
                        } else if (blockType === "custom") {
                            blockObj.component =
                                blockDiv.querySelector(
                                    ".block-component"
                                ).value;
                            const dataLines = blockDiv
                                .querySelector(".block-custom-data")
                                .value.split("\n");
                            const dataObj = {};
                            dataLines.forEach((line) => {
                                const [key, value] = line.split("=");
                                if (key && value) {
                                    dataObj[key.trim()] = value.trim();
                                }
                            });
                            blockObj.data = dataObj;
                        }
                        sectionObj.content.push(blockObj);
                    });
                    blogPost.content.push(sectionObj);
                });
                return blogPost;
            }

            // Function to render preview using the built JSON
            function renderPreview(blogPost) {
                const previewContainer =
                    document.getElementById("preview-container");
                previewContainer.innerHTML = "";

                // Render header
                const headerDiv = document.createElement("div");
                headerDiv.className = "blog-header";
                const titleElem = document.createElement("h1");
                titleElem.innerText = blogPost.title;
                headerDiv.appendChild(titleElem);
                const metaElem = document.createElement("p");
                metaElem.innerText = `By ${blogPost.author} | ${new Date(
                    blogPost.publish_date
                ).toDateString()}`;
                headerDiv.appendChild(metaElem);
                const mainImg = document.createElement("img");
                mainImg.src = blogPost.main_image.url;
                mainImg.alt = blogPost.main_image.alt;
                headerDiv.appendChild(mainImg);
                previewContainer.appendChild(headerDiv);

                // Render each section
                blogPost.content.forEach((section) => {
                    const sectionDiv = document.createElement("div");
                    sectionDiv.className = "section";
                    const headingElem = document.createElement(
                        section.heading.level
                    );
                    headingElem.className = "section-heading";
                    headingElem.innerText = section.heading.text;
                    sectionDiv.appendChild(headingElem);
                    section.content.forEach((block) => {
                        sectionDiv.appendChild(renderBlockForPreview(block));
                    });
                    previewContainer.appendChild(sectionDiv);
                });
            }

            // Function to render individual content blocks in preview
            function renderBlockForPreview(block) {
                let element;
                switch (block.type) {
                    case "paragraph":
                        element = document.createElement("p");
                        element.className = "paragraph";
                        element.innerText = block.text;
                        break;
                    case "image":
                        element = document.createElement("img");
                        element.src = block.url;
                        element.alt = block.alt;
                        break;
                    case "video":
                        element = document.createElement("iframe");
                        element.src = block.url;
                        element.frameBorder = "0";
                        element.allow =
                            "accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture";
                        element.allowFullscreen = true;
                        break;
                    case "steps":
                        element = document.createElement("div");
                        element.className = "steps";
                        block.steps.forEach((step) => {
                            const stepDiv = document.createElement("div");
                            stepDiv.className = "step";
                            const sn = document.createElement("span");
                            sn.className = "step-number";
                            sn.innerText = step.number;
                            stepDiv.appendChild(sn);
                            const st = document.createElement("strong");
                            st.innerText = step.title;
                            stepDiv.appendChild(st);
                            const sd = document.createElement("p");
                            sd.innerText = step.description;
                            stepDiv.appendChild(sd);
                            if (step.media) {
                                stepDiv.appendChild(
                                    renderBlockForPreview(step.media)
                                );
                            }
                            element.appendChild(stepDiv);
                        });
                        break;
                    case "quote":
                        element = document.createElement("blockquote");
                        element.innerText = block.text;
                        if (block.citation) {
                            const cite = document.createElement("cite");
                            cite.innerText = " - " + block.citation;
                            element.appendChild(cite);
                        }
                        break;
                    case "list":
                        element = block.ordered
                            ? document.createElement("ol")
                            : document.createElement("ul");
                        block.items.forEach((item) => {
                            const li = document.createElement("li");
                            li.innerText = item;
                            element.appendChild(li);
                        });
                        element.className = "list";
                        break;
                    case "code":
                        element = document.createElement("pre");
                        element.className = "code";
                        const codeElem = document.createElement("code");
                        codeElem.innerText = block.code;
                        element.appendChild(codeElem);
                        break;
                    case "embed":
                        element = document.createElement("iframe");
                        element.src = block.url;
                        element.frameBorder = "0";
                        element.allowFullscreen = true;
                        break;
                    case "gallery":
                        element = document.createElement("div");
                        element.className = "gallery";
                        block.images.forEach((img) => {
                            const imgElem = document.createElement("img");
                            imgElem.src = img.url;
                            imgElem.alt = img.alt;
                            element.appendChild(imgElem);
                        });
                        break;
                    case "custom":
                        element = document.createElement("div");
                        element.className = "custom";
                        if (block.component === "testimonial") {
                            element.innerHTML = `<strong>${block.data.author}</strong><p>${block.data.quote}</p><p>Rating: ${block.data.rating} / 5</p>`;
                        } else {
                            element.innerText = JSON.stringify(block.data);
                        }
                        break;
                    case "link":
                        element = document.createElement("a");
                        element.href = block.url;
                        element.innerText = block.text;
                        element.className = "internal-link";
                        break;
                    default:
                        element = document.createElement("div");
                        element.innerText = "Unknown block type";
                }
                return element;
            }

            // Generate preview on button click
            document
                .getElementById("generate-preview")
                .addEventListener("click", function () {
                    const blogJSON = buildBlogJSON();
                    renderPreview(blogJSON);
                    console.log("Generated Blog JSON:", blogJSON);
                });

            // Submit post: Check confirmation inputs before submission
            document
                .getElementById("submit-post")
                .addEventListener("click", function () {
                    const blogJSON = buildBlogJSON();
                    console.log("Blog Post Data:", blogJSON);

                    // Check confirmation conditions
                    const reviewed =
                        document.getElementById("reviewed-checkbox").checked;
                    const confirmed =
                        document.getElementById("confirm-checkbox").checked;
                    const confirmText = document
                        .getElementById("confirm-text")
                        .value.trim();

                    if (!reviewed || !confirmed || confirmText !== "CONFIRM") {
                        alert(
                            "Please review your preview, check both confirmation boxes, and type CONFIRM before submitting."
                        );
                        return;
                    }

                    // If all conditions are met, display the submitted data
                    const submittedDataDiv =
                        document.getElementById("submitted-data");
                    submittedDataDiv.style.display = "block";
                    submittedDataDiv.innerText = JSON.stringify(
                        blogJSON,
                        null,
                        2
                    );

                    alert(
                        "Post submitted! Check the Submitted Data section for JSON output."
                    );
                });
        </script>
    </body>
</html>
