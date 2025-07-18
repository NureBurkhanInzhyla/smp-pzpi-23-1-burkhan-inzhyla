<?php
class User {
    public $name;
    public $age;

    public function setProfile() {
        $name = readline("Введіть ваше ім'я: ");
        if (empty($name) || !preg_match('/[a-zA-Zа-яА-ЯіІїЇєЄ]/u', $name)) {
            echo "ПОМИЛКА! Імʼя не може бути порожнім і повинно містити хоча б одну літеру.\n";
            return;
        }

        $age = readline("Введіть ваш вік: ");
        if (!is_numeric($age) || $age < 7 || $age > 150) {
            echo "ПОМИЛКА! Вік має бути числом від 7 до 150.\n";
            return;
        }

        $this->name = $name;
        $this->age = (int)$age;
        echo "Профіль: $this->name, $this->age років.\n";
    }
}

function showMenu() {
    echo "################################\n";
    echo "# ПРОДОВОЛЬЧИЙ МАГАЗИН \"ВЕСНА\" #\n";
    echo "################################\n";
    echo "1 Вибрати товари\n";
    echo "2 Отримати підсумковий рахунок\n";
    echo "3 Налаштувати свій профіль\n";
    echo "0 Вийти з програми\n";
    return (int)readline("Введіть команду: ");
}

$products = [
    "Молоко пастеризоване" => 12,
    "Хліб чорний" => 9,
    "Сир білий" => 21,
    "Сметана 20%" => 25,
    "Кефір 1%" => 19,
    "Вода газована" => 18,
    "Печиво 'Весна'" => 14
];

$selectedProducts = [];

function showProducts($products) {
    echo "№  НАЗВА                      ЦІНА\n";
    $index = 1;
    foreach ($products as $name => $price) {
        $nameLength = iconv_strlen($name, 'UTF-8');
        $namePadded = $name . str_repeat(' ', 26 - $nameLength); 

        printf("%-3d%s %d\n", $index++, $namePadded, $price);
    }

    echo "----------------------------------\n";
    echo "0  ПОВЕРНУТИСЯ\n";
    return readline("Виберіть товар: ");
}



function buyProducts(&$products, &$selectedProducts){
    while(true){
        $choice = showProducts($products);
    
        if ($choice == '0') {
            return;  
        }
    
        if (is_numeric($choice) && $choice > 0 && $choice <= count($products)) {
            $index = 1;
            foreach ($products as $name => $price) {
                if ($index == $choice) {
                    $quantity = readline("Введіть кількість, штук: ");;
    
                    if (!is_numeric($quantity) || $quantity < 0 || $quantity > 100) {
                        echo "ПОМИЛКА! Введіть коректну кількість.\n";
                    }
    
                    if (isset($selectedProducts[$name])) {
                        if($quantity == 0){
                            unset($selectedProducts[$name]);
                            echo "Товар '$name' видалено з кошика.\n";
                        }
                        else{
                            $selectedProducts[$name]['кількість'] += (int)$quantity;
                        }
                    } else {
                        $selectedProducts[$name] = [
                            'ціна' => $price,
                            'кількість' => (int)$quantity
                        ];
                    }
    
                    echo "Товар '$name' у кількості $quantity додано до кошика.\n";
                }
                $index++;
            }
        } else {
            echo "ПОМИЛКА! ВКАЗАНО НЕПРАВИЛЬНИЙ НОМЕР ТОВАРУ.\n";
        }
    }
}

function finalAccount(&$selectedProducts){
    if (empty($selectedProducts)) {
        echo "Ваш кошик порожній.\n";
        return;
    }

    printf("№  НАЗВА                 ЦІНА  КІЛЬКІСТЬ  ВАРТІСТЬ\n");
    $i = 1;
    $total = 0;

    foreach($selectedProducts as $name => $info){
        $price = $info['ціна'];
        $quantity = $info['кількість'];
        $sum = $price * $quantity;
        $total += $sum;

        $nameLength = iconv_strlen($name, 'UTF-8');
        $namePadded = $name . str_repeat(' ', max(0, 20 - $nameLength));

        printf("%-3d %s %-6d %-10d %-8d\n", $i, $namePadded, $price, $quantity, $sum);
        $i++;
    }

    echo "РАЗОМ ДО CПЛАТИ: $total\n";
}


$user = new User();
while (true) {
    $command = showMenu();;
    if (!in_array($command, ['0', '1', '2', '3'])) {
        echo "ПОМИЛКА! Введіть правильну команду\n";
        echo "\n\n";
        continue;
    }
    switch ($command) {
        case '1':
            buyProducts($products, $selectedProducts);
            break;
        case '2':
            finalAccount($selectedProducts);
            break;
        case '3':
            $user->setProfile();
            break;
        case '0':
            echo "Вихід з програми...\n";
            exit;
    }

    echo "\n\n";

}

?>
