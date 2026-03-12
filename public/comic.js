const main = document.getElementById("main");
const pageUrls = JSON.parse(main.dataset.pageUrls);

// Page state
let index = 0;
const elements = [];

function getElement(i) {
  if (i < 0 || i >= pageUrls.length) return undefined;
  if (!elements[i]) {
    const img = new Image();
    img.src = pageUrls[i];
    img.className = "comic-page";
    img.decode().catch(() => {});
    elements[i] = img;
  }
  return elements[i];
}

function preloadAround(i) {
  for (const offset of [0, 1, -1, 2, 3, 4]) {
    getElement(i + offset);
  }
  return getElement(i);
}

const lastIndex = () => pageUrls.length - 1;

function previous() {
  index = index <= 0 ? lastIndex() : index - 1;
  return preloadAround(index);
}

function current() {
  return preloadAround(index);
}

function next() {
  index = index >= lastIndex() ? 0 : index + 1;
  return preloadAround(index);
}

// Hash-based URL state
function hashedUrl(i) {
  const url = new URL(location.href);
  url.hash = "p" + i;
  return url.href;
}

function indexFromHash() {
  const match = /p(\d+)/.exec(location.hash);
  return match ? parseInt(match[1], 10) : undefined;
}

// Fit image to viewport, centered
function fitToWindow(el) {
  const s = el.style;
  s.position = "absolute";
  s.margin = "auto";
  s.inset = "0";
  s.overflow = "hidden";
  s.userSelect = "none";

  const maxWidth = Math.max(document.body.clientWidth, document.documentElement.clientWidth);
  const maxHeight = Math.max(document.body.clientHeight, document.documentElement.clientHeight);
  const ratio = el.naturalHeight / el.naturalWidth;

  if (el.naturalWidth / maxWidth > el.naturalHeight / maxHeight) {
    s.width = maxWidth + "px";
    s.height = maxWidth * ratio + "px";
  } else {
    s.width = maxHeight / ratio + "px";
    s.height = maxHeight + "px";
  }
}

let showGeneration = 0;

function show() {
  const generation = ++showGeneration;
  const img = current();
  location.replace(hashedUrl(index));

  // If already decoded (cached), show immediately
  if (img.naturalWidth > 0) {
    fitToWindow(img);
    main.replaceChildren(img);
    return;
  }

  // Wait for decode, but skip if user already navigated away
  img.decode().then(() => {
    if (generation === showGeneration) {
      fitToWindow(img);
      main.replaceChildren(img);
    }
  }).catch(() => {});
}

function showNext() {
  next();
  show();
}

function showPrevious() {
  previous();
  show();
}

// Initialize from hash
const startIndex = indexFromHash();
if (startIndex !== undefined) {
  index = startIndex;
}
show();

// Keyboard navigation
document.addEventListener("keydown", (e) => {
  if (e.key === "k" || e.key === "ArrowUp") {
    showPrevious();
  } else if (e.key === "j" || e.key === "ArrowDown") {
    showNext();
  }
});

// Wheel navigation
document.addEventListener("wheel", (e) => {
  if (!Number.isFinite(e.deltaY) || e.deltaY === 0) return;
  if (e.deltaY < 0) {
    showPrevious();
  } else {
    showNext();
  }
});

// Click navigation: top half = previous, bottom half = next
main.addEventListener("click", (e) => {
  e.preventDefault();
  if (e.clientY < window.innerHeight / 2) {
    showPrevious();
  } else {
    showNext();
  }
});
