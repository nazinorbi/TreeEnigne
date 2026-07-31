<h1>Itt fogom leírni az architetrúrás jellemzést</h1>
<h3>A projekt 3 fő interfacen nyugszik</h3> 
<ul>
<li>TreeManagerInterface – a fa módosításának és kezelésének műveletei</li>
<li>TreeNodeInterface – egy faelem absztrakciója</li>
<li>TreeRepositoryInterface – a fa tartós tárolása és betöltése</li>
</ul>
<h3>Tree Node</h3>
<br>
<li>Mi pontosan egy TreeNode?</li>
    <p>A Tree Node az egy csomó pont a filogenetikus fában objektum szinten definiálva. Ami tartamz minden olyan adatot amit az sql le tárol</p>
<li>Hogyan kapcsolódik egy node a szülőjéhez és gyermekeihez?</li>
    <P>A Nested Set Model logikai elvét követve</P>
<li>Hogyan azonosítunk egy node-ot?</li>
    <p>nodeId- Ez soha sem változik. mikroszekundum pontosságú UTC timestamp + egy 6 karakteres véleltlen szám.</p>
<li>Hol tároljuk az egyéb metaadatokat?</li>
    <p>A meta adatokat egy külön áló táblában van tárolva és  nodeId külső kulcsal van össze kötve</p>
<li>Mit jelent a verify, és milyen hibákat kell felismernie?</li>
    <p>A verify nem javítja a fát, hanem ellenőrzi, hogy a lft és rgt értékekből rekonstruálható-e egy szabályos hierarchia.<br><br>
        A részfa beillesztése csak akkor tekinthető sikeresnek, ha a módosított főfa Nested Set struktúrája konzisztens,
        és a gyökér lft/rgt intervalluma alapján számított node-szám megegyezik a főfában ténylegesen található node-ok számával.</p>    
<h4>Ellenőriznie kell legalább:</h4>
    <ol>
        <li>Értéktartomány
            <p>- a számozás 1-től indul</p>
            <p>- a maximális értéknek meg kell egyeznie a számláló végértékével</p>
            <p>- nem lehet 0 vagy negatív érték</p>
        </li>
        <li>Egyediség
             <p>- egyetlen lft vagy rgt érték sem ismétlődhet</p>
             <p>- minden érték pontosan egyszer szerepel</p>
        </li>
        <li>Páros/páratlan szabály
             <p>- egy node lft és rgt értékei közül az egyik páratlan, a másik páros.</p>
        </li> 
        <li>Levél ellenőrzése
             <p>rgt - lft = 1 esetén a node levél.</p>
        </li>
        <li>Gyermekek száma
             <p>Egy node akkor lehet egy másik node szülője, ha: parent.lft < child.lft
                AND parent.rgt > child.rgt A közvetlen szülőt pedig az egymásba ágyazott intervallumok alapján kell meghatározni.</p>
        <li>Szülő–gyermek kapcsolat
             <p>A dokumentációban szereplő képlet:
                (parent.rgt - parent.lft - 1) / 2 megadja a szülő részfájának elemszámát, nem pusztán a közvetlen gyermekek számát.</p>
        </li>
    </ol>
<li>Az export/import milyen formátumokat támogasson – például Newick, JSON, esetleg később Nexus?</li>
    <p>Első körben Newick és JSON ami támógatott.</p>
<li>Hogyan kezeljük a konkurens szerkesztést?</li>
    <p>Amikor egy felhasználó elkezd a fő fán egy részfát szerkezteni, akkor az a csomó pont ahova majd a részfája be 
    csatlakozik kap egy bolean értéket. Ezt a tree adatbázisban van tárolva. Továbbá kap egy másik meta adatot is hogy ki szerkezti. 
    Ez akkor jó ha hosszú ideje zárolva van az adott csoó pont. A szerztés végével amit a merge  mű velet jelez a zárolás feloldásra kerül. 
    Így elkerülhető a párhuzamos szerkeztés</p>
</ol>
