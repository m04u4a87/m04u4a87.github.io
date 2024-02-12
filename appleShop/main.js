let shopObj = {};
let selectedColor, selectedModel, selectedStorage
//1.取得資料
window.onload = () => {
  fetchMerchandise("./data/iphone-14-pro.json")
    .then((shopData) => {
      // console.log(shopData);
      shopObj = shopData;
      renderingShop(shopData);
    })
    .catch((err) => {
      alert(`error:${err}`);
    });
};

function fetchMerchandise(url) {
  return fetch(url).then((response) => response.json());
}

function renderingShop(shop) {
  //處理商店標題及顯示價錢(最低)
  const priceArr = shop.specifications.map((spec) => spec.price);
  const minPrice = Math.min(...priceArr);
  const title = shop.title;

  document.querySelector(".shop-content .title-area").innerHTML = `<h1>
                ${title}
              </h1>
              <div class="total-price">
                NT$ ${minPrice} 起
              </div>`;

  //幻燈片
  // const defaultImages = Object.values(shop.images)[0]
  const defaultImages = Object.values(shop.images).reduce(
    (acc, curr) => acc.concat(curr),
    []
  );

  createCarousel(defaultImages);

  //widgets處理(機型、顏色、儲存裝置)
  let widgetsHTML = "";
  shop.widgets.forEach((widget) => {
    widgetsHTML += createWidgetHTML(widget);
  });
  document.querySelector(".spec-widget").innerHTML = widgetsHTML;

  //小計區塊
}

function createWidgetHTML(widget) {
  const items = getWidgetItem(widget.type);
  let itemsHTML = "";

  items.forEach((item) => {
    console.log(item);
    if (widget.type === "color") {
      const theColorObj = shopObj.colors.find(c => c.colorCode === item)
      itemsHTML += `
      <div class="col">
        <div class="border border-secondary-subtle rounded-3 p-4 text-center" role="button" onclick="clickHandler(this,'${widget.type}')" data-color="${item}">
        <img class="w-25" src="${theColorObj.colorImg}"
            alt="${theColorObj.colorName}">
        </div>
      </div>
      `;
    } else if (widget.type === "model") {
      
      itemsHTML += `
      <div class="col">
          <div class="border border-secondary-subtle rounded-3 p-4" role="button" onclick="clickHandler(this,'${widget.type}')" data-model="${item}">${item}</div>
      </div>
      `
    } else if (widget.type === "storage") {
      itemsHTML += `
      <div class="col">
        <div class="border border-secondary-subtle rounded-3 p-4 d-flex justify-content-between"
            role="button" onclick="clickHandler(this,'${widget.type}')" data-storage="${item}">
            <div class="storage-spec">${item}</div>
            <div class="price"></div>
        </div>
      </div>`
    } else {
      itemsHTML += `
      <div class="col">
          <div class="border rounded-3 p-4" role="button">${item}</div>
      </div>
      `;
    }
  });

  const html = `
  <section class="widget-item mb-4 mx-lg-3">
    <h2 class="fs-4">${widget.title} <span class="text-black-50">${widget.subTitle}</span></h2>
    <div class="row row-cols-${widget.col} gy-3">
        ${itemsHTML}
    </div>
</section>`;
  return html;
}

function getWidgetItem(type) {
  console.log(shopObj);
  //從specifications內取得符合的items:
  //model => "iPhone 14 Pro","iPhone 14 Pro Max"
  //color => "","","",""
  //storage => "128G", "256G", "512G", "1TB"

  return new Set(shopObj.specifications.map((spec) => spec[type]));
}

function createCarousel(images) {
  const mainImgArea = document.querySelector(".main-img-area");

  const carouselIndicatorsHTML = createCarouselIndicatorsHTML(images);

  const carouselInnerHTML = createCarouselHTML(images);

  mainImgArea.innerHTML = `
  <div id="carouselExampleAutoplaying" class="carousel slide  sticky-top" data-bs-ride="carousel">
      <div class="carousel-indicators">
          ${carouselIndicatorsHTML}
      </div>
      <div class="carousel-inner">
          ${carouselInnerHTML}
      </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying"
          data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying"
          data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Next</span>
      </button>
    </div>
  `;
}

/**
 * 幻燈片指標區塊
 * @param {String[]} images
 * @returns Indicators Area HTML
 */
function createCarouselIndicatorsHTML(images) {
  //     let html = `<button type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide-to="0"
  //     class="active" aria-current="true" aria-label="Slide 1"></button>
  // <button type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide-to="1"
  //     aria-label="Slide 2"></button>
  // <button type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide-to="2"
  //     aria-label="Slide 3"></button>`
  let html = "";
  images.forEach((img, idx) => {
    html += `<button type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide-to="${idx}"
      class="${
        idx === 0 ? "active" : ""
      }" aria-current="true" aria-label="Slide ${idx + 1}"></button>`;
  });
  return html;
}

/**
 * 幻燈片主圖區區塊
 * @param {String[]} images
 * @returns Carousel Img Area HTML
 */
function createCarouselHTML(images) {
  //     let html = `<div class="carousel-item active">
  //     <img src="./images/iphone-14-pro/iphone-14-pro-finish-select-202209-6-1inch-deeppurple.jpeg"
  //         class="d-block w-100" alt="...">
  // </div>
  // <div class="carousel-item">
  //     <img src="./images/iphone-14-pro/iphone-14-pro-finish-select-202209-6-1inch-deeppurple_AV1.jpeg"
  //         class="d-block w-100" alt="...">
  // </div>
  // <div class="carousel-item">
  //     <img src="./images/iphone-14-pro/iphone-14-pro-finish-select-202209-6-1inch-deeppurple_AV2.jpeg"
  //         class="d-block w-100" alt="...">
  // </div>`
  let html = "";
  images.forEach((img, idx) => {
    html += `<div class="carousel-item ${idx === 0 ? "active" : ""}">
                      <img src="${img}" class="d-block w-100" alt="...">
                  </div>`;
  });
  return html;
}


function clickHandler(element, type){
  // console.log(element.getAttribute(`data-${type}`));
  // console.log(element.dataset[type]);
  // console.log(type);
  if(type === 'color'){
    selectedColor = element.dataset[type]
    //換對應顏色的幻燈片
    createCarousel(shopObj.images[selectedColor])
  }else if(type === 'model'){
    selectedModel = element.dataset[type]
  }else if(type === 'storage'){
    selectedStorage = element.dataset[type];
  }

  getSummary()
}

function getSummary(){
  if(selectedColor && selectedModel && selectedStorage){
    const spec = shopObj.specifications.find(spec => spec.model === selectedModel && spec.color === selectedColor && spec.storage === selectedStorage)

    const price = spec.price.toLocaleString()
    const colorObj = shopObj.colors.find(c => c.colorCode === selectedColor)
    const title = `${selectedModel} ${selectedStorage} ${colorObj.colorName}`
    document.querySelector('.summary-area .product-item').innerHTML = `<div class="title">${title}</div>
    <div class="price fw-bold">NT$ ${price}</div>`
  }
}