
    //回頂端
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

        //一網頁有多分頁時，防止回頂端改變網址，造成被彈回預設頁面
        document.addEventListener('DOMContentLoaded', function() {
        const top = document.querySelector('.top');

        top.addEventListener('click', (event) => {
            event.preventDefault(); 
            scrollToTop();
        });

        function scrollToTop() {
            const scroll = document.documentElement.scrollTop || document.body.scrollTop;
            if (scroll > 0) {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth' /*平滑捲動*/
                });
            }
        }
    });
    
    // 抓取"你的身分"的三顆按鈕
    const yourSex = document.querySelectorAll('.yourSex','yourRelationship');
    const yourRelationship = document.querySelectorAll('.yourRelationship');
    // 預設目前選擇的按鈕
    let selectedSexButton = null;
    let selectedRelationshipButton = null;
    //增加點擊按鈕，按鈕會變色&單選事件
    yourSex.forEach(button => {
        button.addEventListener('click', function() {
            //如果已經有選著按鈕，或目前點著的按鈕，不是原本選著的按鈕，就移除原本按鈕的藍色
            if (selectedSexButton !== null && selectedSexButton !== button) {
                selectedSexButton.classList.remove('blue-background');
            }
            //將點著的按鈕切成藍色，如果原本就是藍色，則切換為灰色
            button.classList.toggle('blue-background');
            selectedSexButton = button.classList.contains('blue-background') ? button : null;
        });
    });
    yourRelationship.forEach(button => {
        button.addEventListener('click', function() {
            //如果已經有選著按鈕，或目前點著的按鈕，不是原本選著的按鈕，就移除原本按鈕的藍色
            if (selectedRelationshipButton !== null && selectedRelationshipButton !== button) {
                selectedRelationshipButton.classList.remove('blue-background');
            }
            //將點著的按鈕切成藍色，如果原本就是藍色，則切換為灰色
            button.classList.toggle('blue-background');
            selectedRelationshipButton = button.classList.contains('blue-background') ? button : null;
        });
    });

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










