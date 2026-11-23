const PRESET_BUILDS = {
  fps: {
    entry: {
      priceRange: "₱35,000 - ₱48,000",
      target: "1080p / 240+ FPS (Esports)",
      specs: [
        "AMD Ryzen 5 5600",
        "NVIDIA RTX 4060 8GB",
        "16GB DDR4 3600MHz",
        "B550M WiFi Motherboard",
        "1TB NVMe Gen4 SSD",
        "650W 80+ Bronze PSU"
      ],
      items: [
        { name: "AMD Ryzen 5 5600 Processor", price: 6495 },
        { name: "NVIDIA GeForce RTX 4060 8GB", price: 18500 },
        { name: "16GB (2x8GB) DDR4 3600MHz RAM", price: 2850 },
        { name: "MSI B550M PRO-VDH WiFi Motherboard", price: 6200 },
        { name: "1TB NVMe Gen4 M.2 SSD", price: 3950 },
        { name: "650W 80+ Bronze Power Supply", price: 3200 }
      ]
    },
    mid: {
      priceRange: "₱80,000 - ₱105,000",
      target: "1080p & 1440p / 360+ FPS",
      specs: [
        "AMD Ryzen 7 7800X3D",
        "NVIDIA RTX 4070 Super 12GB",
        "32GB DDR5 6000MHz CL30",
        "B650M WiFi Motherboard",
        "2TB Gen4 NVMe SSD",
        "750W 80+ Gold PSU"
      ],
      items: [
        { name: "AMD Ryzen 7 7800X3D Processor", price: 23950 },
        { name: "NVIDIA GeForce RTX 4070 Super 12GB", price: 38900 },
        { name: "32GB (2x16GB) DDR5 6000MHz CL30 RAM", price: 7450 },
        { name: "ASUS TUF Gaming B650M-Plus WiFi", price: 10800 },
        { name: "2TB Gen4 NVMe M.2 SSD", price: 7900 },
        { name: "750W 80+ Gold Modular PSU", price: 5800 }
      ]
    },
    extremist: {
      priceRange: "₱165,000 - ₱210,000",
      target: "1440p / 480+ FPS Ultra-Low Latency",
      specs: [
        "AMD Ryzen 7 9800X3D",
        "NVIDIA RTX 5080 16GB",
        "64GB DDR5 6400MHz CL30",
        "X870E Gaming Motherboard",
        "2TB Gen5 NVMe SSD",
        "1000W 80+ Platinum PSU"
      ],
      items: [
        { name: "AMD Ryzen 7 9800X3D Processor", price: 31500 },
        { name: "NVIDIA GeForce RTX 5080 16GB", price: 78500 },
        { name: "64GB (2x32GB) DDR5 6400MHz CL30 RAM", price: 15900 },
        { name: "ROG Strix X870E-E Gaming WiFi", price: 26500 },
        { name: "2TB Gen5 NVMe M.2 SSD", price: 14800 },
        { name: "1000W 80+ Platinum ATX 3.1 PSU", price: 11200 }
      ]
    }
  },

  "4k": {
    entry: {
      priceRange: "₱55,000 - ₱70,000",
      target: "4K / 60fps (DLSS / FSR Balanced)",
      specs: [
        "Intel Core i5-13400F",
        "AMD Radeon RX 7700 XT 12GB",
        "32GB DDR5 5600MHz",
        "B760M WiFi Motherboard",
        "1TB Gen4 NVMe SSD",
        "750W 80+ Bronze PSU"
      ],
      items: [
        { name: "Intel Core i5-13400F Processor", price: 10950 },
        { name: "AMD Radeon RX 7700 XT 12GB", price: 26900 },
        { name: "32GB (2x16GB) DDR5 5600MHz RAM", price: 6400 },
        { name: "ASUS PRIME B760M-A WiFi DDR5", price: 8967 },
        { name: "1TB Gen4 NVMe M.2 SSD", price: 4100 },
        { name: "750W 80+ Bronze Power Supply", price: 3850 }
      ]
    },
    mid: {
      priceRange: "₱110,000 - ₱140,000",
      target: "4K / 100-120fps High-Ultra",
      specs: [
        "AMD Ryzen 7 7700X",
        "NVIDIA RTX 4080 Super 16GB",
        "32GB DDR5 6000MHz",
        "B650 ATX WiFi Motherboard",
        "2TB Gen4 NVMe SSD",
        "850W 80+ Gold ATX 3.0 PSU"
      ],
      items: [
        { name: "AMD Ryzen 7 7700X Processor", price: 17950 },
        { name: "NVIDIA GeForce RTX 4080 Super 16GB", price: 66500 },
        { name: "32GB (2x16GB) DDR5 6000MHz RAM", price: 7200 },
        { name: "MSI MAG B650 Tomahawk WiFi", price: 12400 },
        { name: "2TB Gen4 NVMe M.2 SSD", price: 7900 },
        { name: "850W 80+ Gold ATX 3.0 PSU", price: 6950 }
      ]
    },
    extremist: {
      priceRange: "₱215,000 - ₱250,000",
      target: "4K / 144fps+ Native & Path Tracing",
      specs: [
        "AMD Ryzen 9 9950X3D",
        "NVIDIA RTX 5090 32GB",
        "64GB DDR5 6400MHz",
        "X870E Flagship Motherboard",
        "4TB Gen5 NVMe SSD",
        "1200W 80+ Platinum ATX 3.1 PSU"
      ],
      items: [
        { name: "AMD Ryzen 9 9950X3D Processor", price: 41500 },
        { name: "NVIDIA GeForce RTX 5090 32GB", price: 138000 },
        { name: "64GB (2x32GB) DDR5 6400MHz RAM", price: 15900 },
        { name: "MSI MEG X870E Godlike / Ace", price: 25500 },
        { name: "4TB Gen5 NVMe M.2 SSD", price: 22500 },
        { name: "1200W 80+ Platinum ATX 3.1 PSU", price: 13500 }
      ]
    }
  },

  streaming: {
    entry: {
      priceRange: "₱45,000 - ₱60,000",
      target: "1080p 60fps Stream + 120fps Play (NVENC)",
      specs: [
        "Intel Core i5-13500 (14-Core)",
        "NVIDIA RTX 4060 Ti 8GB",
        "32GB DDR4 3200MHz",
        "B760M WiFi Motherboard",
        "1TB NVMe + 2TB HDD",
        "650W 80+ Gold PSU"
      ],
      items: [
        { name: "Intel Core i5-13500 Processor", price: 13400 },
        { name: "NVIDIA GeForce RTX 4060 Ti 8GB", price: 23900 },
        { name: "32GB (2x16GB) DDR4 3200MHz RAM", price: 4800 },
        { name: "ASUS PRIME B760M-A WiFi", price: 8967 },
        { name: "1TB NVMe SSD + 2TB Archive HDD", price: 6900 },
        { name: "650W 80+ Gold Power Supply", price: 4500 }
      ]
    },
    mid: {
      priceRange: "₱95,000 - ₱125,000",
      target: "1440p Gaming + 1080p/4K AV1 Stream",
      specs: [
        "Intel Core Ultra 7 265K",
        "NVIDIA RTX 4070 Ti Super 16GB",
        "32GB DDR5 6400MHz",
        "Z890 WiFi Motherboard",
        "2TB Gen4 NVMe SSD",
        "850W 80+ Gold ATX 3.0 PSU"
      ],
      items: [
        { name: "Intel Core Ultra 7 265K Processor", price: 24900 },
        { name: "NVIDIA GeForce RTX 4070 Ti Super 16GB", price: 51500 },
        { name: "32GB (2x16GB) DDR5 6400MHz RAM", price: 7800 },
        { name: "MSI PRO Z890-P WiFi Motherboard", price: 14500 },
        { name: "2TB Gen4 NVMe M.2 SSD", price: 7900 },
        { name: "850W 80+ Gold ATX 3.0 PSU", price: 6950 }
      ]
    },
    extremist: {
      priceRange: "₱175,000 - ₱220,000",
      target: "4K Gaming + Dual-Encoder 4K60 HDR Stream",
      specs: [
        "Intel Core Ultra 9 285K",
        "NVIDIA RTX 5080 16GB (Dual AV1)",
        "64GB DDR5 7200MHz",
        "Z890 Creator WiFi Motherboard",
        "4TB Gen4 NVMe SSD",
        "1000W 80+ Platinum PSU"
      ],
      items: [
        { name: "Intel Core Ultra 9 285K Processor", price: 40150 },
        { name: "NVIDIA GeForce RTX 5080 16GB", price: 78500 },
        { name: "64GB (2x32GB) DDR5 7200MHz RAM", price: 17500 },
        { name: "ASUS ProArt Z890-Creator WiFi", price: 27900 },
        { name: "4TB Gen4 NVMe M.2 SSD", price: 15800 },
        { name: "1000W 80+ Platinum ATX 3.1 PSU", price: 11200 }
      ]
    }
  },

  editing: {
    entry: {
      priceRange: "₱50,000 - ₱68,000",
      target: "1080p/4K Timeline Scrubbing & Light 3D",
      specs: [
        "Intel Core i5-14600K (QuickSync)",
        "NVIDIA RTX 3060 12GB VRAM",
        "32GB DDR5 5600MHz",
        "B760M Pro WiFi Motherboard",
        "1TB Gen4 NVMe Scratch SSD",
        "750W 80+ Gold PSU"
      ],
      items: [
        { name: "Intel Core i5-14600K Processor", price: 17500 },
        { name: "NVIDIA GeForce RTX 3060 12GB", price: 17800 },
        { name: "32GB (2x16GB) DDR5 5600MHz RAM", price: 6400 },
        { name: "MSI PRO B760M-A WiFi DDR5", price: 9200 },
        { name: "1TB Gen4 NVMe M.2 SSD", price: 4300 },
        { name: "750W 80+ Gold Power Supply", price: 5400 }
      ]
    },
    mid: {
      priceRange: "₱115,000 - ₱145,000",
      target: "4K RAW Editing & Blender GPU Rendering",
      specs: [
        "AMD Ryzen 9 7900X (12-Core)",
        "NVIDIA RTX 4070 Ti Super 16GB",
        "64GB DDR5 6000MHz",
        "B650 Creator WiFi Motherboard",
        "2TB Gen4 NVMe + 2TB Scratch SSD",
        "850W 80+ Gold PSU"
      ],
      items: [
        { name: "AMD Ryzen 9 7900X Processor", price: 23500 },
        { name: "NVIDIA GeForce RTX 4070 Ti Super 16GB", price: 51500 },
        { name: "64GB (2x32GB) DDR5 6000MHz RAM", price: 14200 },
        { name: "ASUS ProArt B650-Creator Motherboard", price: 15800 },
        { name: "2x 2TB Gen4 NVMe M.2 SSDs", price: 15400 },
        { name: "850W 80+ Gold ATX 3.0 PSU", price: 6950 }
      ]
    },
    extremist: {
      priceRange: "₱210,000 - ₱250,000",
      target: "8K ProRes / Heavy Unreal Engine & 3D Scenes",
      specs: [
        "AMD Ryzen 9 9950X (16-Core)",
        "NVIDIA RTX 5090 32GB VRAM",
        "128GB DDR5 6000MHz",
        "X870E Creator WiFi Motherboard",
        "4TB Gen5 NVMe + 4TB Gen4 Scratch",
        "1200W 80+ Platinum PSU"
      ],
      items: [
        { name: "AMD Ryzen 9 9950X Processor", price: 37500 },
        { name: "NVIDIA GeForce RTX 5090 32GB", price: 138000 },
        { name: "128GB (4x32GB) DDR5 6000MHz RAM", price: 28500 },
        { name: "ASUS ProArt X870E-Creator WiFi", price: 27900 },
        { name: "4TB Gen5 + 4TB Gen4 NVMe SSDs", price: 36000 },
        { name: "1200W 80+ Platinum ATX 3.1 PSU", price: 13500 }
      ]
    }
  }
};

let currentUse = "fps";
let currentTier = "mid";

const formatPHP = (amount) =>
  "₱" + Number(amount).toLocaleString("en-PH", {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  });

function renderPresetCards(useKey) {
  const buildsForUse = PRESET_BUILDS[useKey];
  if (!buildsForUse) return;

  document.querySelectorAll(".preset-card").forEach((card) => {
    const tier = card.dataset.tier;
    const data = buildsForUse[tier];
    if (!data) return;

    card.querySelector(".preset-price").textContent = data.priceRange;
    card.querySelector(".target-value").textContent = data.target;

    const specsList = card.querySelector(".preset-specs");
    specsList.innerHTML = data.specs.map((spec) => `<li>${spec}</li>`).join("");
  });
}

function renderComponentList(useKey, tierKey) {
  const componentList = document.getElementById("component-list");
  const buildData = PRESET_BUILDS[useKey]?.[tierKey];
  if (!componentList || !buildData) return;

  componentList.innerHTML = buildData.items
    .map(
      (item) => `
      <article class="component-item" data-name="${item.name}" data-price="${item.price.toFixed(2)}">
        <div class="item-info">
          <p><strong>ITEM:</strong> ${item.name}</p>
          <p><strong>PRICE:</strong> ${formatPHP(item.price)}</p>
        </div>
        <div class="item-controls">
          <div class="quantity-picker">
            <input type="number" class="quantity" value="1" min="1" max="99" aria-label="Quantity for ${item.name}">
          </div>
          <button type="button" class="remove-btn">Remove</button>
          <button type="button" class="edit-btn">Edit</button>
        </div>
      </article>
    `
    )
    .join("");

  updateTotal();
}

function updateTotal() {
  let total = 0;
  document.querySelectorAll("#component-list .component-item").forEach((item) => {
    const price = parseFloat(item.dataset.price) || 0;
    const qty = parseInt(item.querySelector(".quantity")?.value, 10) || 1;
    total += price * qty;
  });

  const totalDisplay = document.getElementById("build-total-display");
  if (totalDisplay) {
    totalDisplay.textContent = formatPHP(total);
  }
}

document.addEventListener("DOMContentLoaded", () => {
  const budgetSlider = document.getElementById("budget-range");
  const budgetDisplay = document.getElementById("current-budget-display");

  if (budgetSlider && budgetDisplay) {
    budgetSlider.addEventListener("input", () => {
      budgetDisplay.textContent =
        "₱" + Number(budgetSlider.value).toLocaleString("en-PH");
    });
  }

  // Primary Use buttons (FPS, 4K Gaming, Streaming, 3D/Video Editing)
  document.querySelectorAll(".use-card").forEach((btn) => {
    btn.addEventListener("click", () => {
      document.querySelectorAll(".use-card").forEach((b) => b.classList.remove("active"));
      btn.classList.add("active");

      currentUse = btn.dataset.use;
      renderPresetCards(currentUse);
      renderComponentList(currentUse, currentTier);
    });
  });

  // Preset Tier cards (Entry, Mid, Extremist)
  document.querySelectorAll(".preset-card").forEach((card) => {
    card.addEventListener("click", () => {
      document.querySelectorAll(".preset-card").forEach((c) => c.classList.remove("active"));
      card.classList.add("active");

      currentTier = card.dataset.tier;
      renderComponentList(currentUse, currentTier);
    });
  });

  // Quantity change and Remove button via event delegation
  const componentList = document.getElementById("component-list");
  if (componentList) {
    componentList.addEventListener("input", (e) => {
      if (e.target.classList.contains("quantity")) {
        updateTotal();
      }
    });

    componentList.addEventListener("click", (e) => {
      if (e.target.classList.contains("remove-btn")) {
        e.target.closest(".component-item")?.remove();
        updateTotal();
      }
    });
  }

  // Mode toggle (Preset vs Custom Request)
  document.querySelectorAll(".mode-btn").forEach((btn) => {
    btn.addEventListener("click", () => {
      document.querySelectorAll(".mode-btn").forEach((b) => b.classList.remove("active"));
      btn.classList.add("active");
    });
  });

  // Initial render
  renderPresetCards(currentUse);
  renderComponentList(currentUse, currentTier);
});