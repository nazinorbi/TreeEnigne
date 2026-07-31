<h1>Itt fogom leírni az architetrúrás jellemzést</h1>
<h3>A projekt 3 fő interfacen nyugszik</h3> 
<ul>
<li>TreeManagerInterface – a fa módosításának és kezelésének műveletei</li>
<li>TreeNodeInterface – egy faelem absztrakciója</li>
<li>TreeRepositoryInterface – a fa tartós tárolása és betöltése</li>
</ul>
<h3>Tree Node</h3>
<ol>
<li>Mi pontosan egy TreeNode?</li>
    <p>A Tree Node az egy csomó pont a filogenetikus fában objektum szinten definiálva. Ami tartamz minden olyan adatot amit az sql le tárol</p>
<li>Hogyan kapcsolódik egy node a szülőjéhez és gyermekeihez?</li>
    <P>A Nested Set Model logikai elvét követve</P>
<li>Hogyan azonosítunk egy node-ot?</li>
    <p>nodeId- Ez soha sem változik. A szám képzése a lft és rgt értékéből tevődik össze: lft+rgt. pl. 5 és 65 akkor az id értéke->565</p>
<li>Hol tároljuk az egyéb metaadatokat?</li>
    <p>A meta adatokat egy külön áló táblában van tárolva és  nodeId külső kulcsal van össze kötve</p>
<li>Mit jelent a verify, és milyen hibákat kell felismernie?</li>
    <p>Le kell ellenőriznie hogy a kapott objektum az egy Tree Node objektum</p>
<li>Az export/import milyen formátumokat támogasson – például Newick, JSON, esetleg később Nexus?</li>
    <p>Első körben Newick és JSON ami támógatott.</p>
<li>Hogyan kezeljük a konkurens szerkesztést?</li>
    <p>Amikor egy felhasználó elkezd a fő fán egy részfát szerkezteni, akkor az a csomó pont ahova majd a részfája be 
    csatlakozik kap egy bolean értéket. Ezt a tree adatbázisban van tárolva. Továbbá kap egy másik meta adatot is hogy ki szerkezti. 
    Ez akkor jó ha hosszú ideje zárolva van az adott csoó pont. A szerztés végével amit a merge  mű velet jelez a zárolás feloldásra kerül. 
    Így elkerülhető a párhuzamos szerkeztés</p>
</ol>
