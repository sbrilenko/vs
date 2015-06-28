<div style="text-align: center;cursor:default;">Вы вошли как  <strong><?=($DLL -> getUser($_SESSION['userID']))?$DLL -> getUser($_SESSION['userID']):'<span style="color:red">unknown</span>';?></strong> | <a href='/admin/in' title='выход'>Выход</a></div>

<div class='bottom_menu'>
    &ensp;
    <!--<?=($tut == 'order')? "<span style='cursor:default;'": "<a";?> 
    style='line-height:20px' href='/admin/' title='Список производителей' <?php echo ($tut == 'order') ? "class='tut'" : ""; ?> >Заказы
    <?=($tut=='order') ? "</span>" : "</a>";?> 
    |-->
    <?=($tut == 'firm')? "<span style='cursor:default;'": "<a";?> 
    style='line-height:20px' href='/admin/firm' title='Список производителей' <?php echo ($tut == 'firm') ? "class='tut'" : ""; ?> >Производители
    <?=($tut=='firm') ? "</span>" : "</a>";?> 
    |
     <?=($tut == 'category')? "<span style='cursor:default;'": "<a";?> 
    style='line-height:20px' href='/admin/category' title='Категории продукции' <?php echo ($tut == 'category') ? "class='tut'" : ""; ?> >Категории продукции
    <?=($tut=='category') ? "</span>" : "</a>";?> 
    |
    <?=($tut == 'group')? "<span style='cursor:default;'": "<a";?> 
    style='line-height:20px' href='/admin/group' title='Продукция' <?php echo ($tut == 'group') ? "class='tut'" : ""; ?> >Продукция
    <?=($tut=='group') ? "</span>" : "</a>";?> 
    |
    <?=($tut == 'subgroup')? "<span style='cursor:default;'": "<a";?> 
    style='line-height:20px' href='/admin/subgroup' title='Виды продукции' <?php echo ($tut == 'subgroup') ? "class='tut'" : ""; ?> >Виды продукции
    <?=($tut=='subgroup') ? "</span>" : "</a>";?> 
    |
    <?=($tut == 'product')? "<span style='cursor:default;'": "<a";?> 
    style='line-height:20px' href='/admin/product' title='Ассортимент' <?php echo ($tut == 'product') ? "class='tut'" : ""; ?> >Ассортимент
    <?=($tut=='product') ? "</span>" : "</a>";?> 
    <!--|
    <?=($tut == 'dial')? "<span style='cursor:default;'": "<a";?> 
    style='line-height:20px' href='/admin/dial' title='Наборы' <?php echo ($tut == 'dial') ? "class='tut'" : ""; ?> >Наборы
    <?=($tut=='dial') ? "</span>" : "</a>";?> |
    <?=($tut == 'clients')? "<span style='cursor:default;'": "<a";?> 
    style='line-height:20px' href='/admin/clients' title='Клиенты' <?php echo ($tut == 'clients') ? "class='tut'" : ""; ?> >Клиенты
    <?=($tut=='clients') ? "</span>" : "</a>";?> -->
    
</div>