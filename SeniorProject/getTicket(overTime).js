
    //當已經在頂端，回頂端符號隱藏
    const arrow = document.querySelector('.top');  /*透過選擇器(.top)，取得DOM元素，放進變數arrow*/
        window.addEventListener('scroll', () =>     /*針對「視窗」，新增「滾動」這個事件，進行監聽*/
            {
                const scroll = window.scrollY;         /*將滾動視窗y軸這件事，放進變數scroll*/

                if (scroll > 0) {                           /*如果變數scroll(滾動視窗y軸)大於0*/
                    arrow.classList.add('visible');         /*就在變數arrow的class，新增一個叫做visible的class*/   
                } else {                                    /*否則移除叫做visible的class*/
                    arrow.classList.remove('visible');
                }
            }
        );

        function resetCheckbox() {
            var checkbox = document.getElementById('profile-container-switch');
            checkbox.checked = false;
        }
        
        window.addEventListener('resize', function () {
            // Add your responsive logic here
            resetCheckbox();
        });
        
        // Call resetCheckbox initially to handle the initial state
        resetCheckbox();
        
        
        
        // 獲取側邊選單開關的 checkbox 和 overlay 元素
        const menuSwitch = document.getElementById('menu-switch');
        const overlay = document.querySelector('.overlay');
        
        // 為 checkbox 添加事件監聽器
        menuSwitch.addEventListener('change', function() {
            // 根據 checkbox 是否被選中來顯示或隱藏 overlay
            if (menuSwitch.checked) {
                overlay.style.display = 'block';
            } else {
                overlay.style.display = 'none';
            }
        });