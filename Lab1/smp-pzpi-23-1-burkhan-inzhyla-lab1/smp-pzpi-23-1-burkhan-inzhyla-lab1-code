#!/bin/bash


if [[ "$1" == "--help" ]]; then
    echo "Синтаксис: $0 [--help | --version] [-q|--quiet] <вхідний_csv_файл> [група]"
    echo ""
    echo "Опис:"
    echo "  Скрипт для обробки CSV-розкладу з CIST (NURE) та"
    echo "  формування файлу, придатного для імпорту в Google Calendar."
    echo ""
    echo "Параметри:"
    echo "  --help        Показати цей текст та завершити виконання"
    echo "  --version     Вивести версію скрипта"
    echo "  -q, --quiet   Приглушити виведення в консоль"
    echo "  група         Назва академічної групи (необов’язково)"
    echo "  вхідний_csv_файл  Файл розкладу для обробки"
    exit 0
fi

if [[ "$1" == "--version" ]]; then
    echo "Скрипт для експорту розкладу в Google Calendar — версія 1.0"
    exit 0
fi

quiet=0
if [[ "$1" == "-q" || "$1" == "--quiet" ]]; then
    quiet=1
    shift
fi

file="$1"
group="$2"


if [[ ! -f "$file" ]]; then
    echo "ПОМИЛКА: файл '$file' не знайдено!" >&2
    exit 1
fi


if ! iconv -f UTF-8 -t UTF-8 "$file" >/dev/null 2>&1; then
    temp_file=$(mktemp)
    iconv -f WINDOWS-1251 -t UTF-8 "$file" > "$temp_file"
    mv "$temp_file" "$file"
fi


awk -v RS='"' '
NR % 2 == 0 {
    gsub(",", "|", $0);
    
    fields[++count] = $0;
    
    if (count == 13) {
        for (i = 1; i <= 13; i++) {
            printf "\"%s\"%s", fields[i], (i < 13 ? "," : "\n");
        }
        count = 0;
    }
}
' "$file" > fixed_file.csv


date=$(echo "$file" | grep -oE '[0-9]{2}_[0-9]{2}_20[0-9]{2}')
outFile="Google_TimeTable_${date}.csv"

> "$outFile"


if [[ -z "$group" ]]; then
    groups=()
    while IFS= read -r line; do
        groups+=("$line")
    done < <(grep -oE 'ПЗПІ-[0-9]+-[0-9]+' fixed_file.csv | sort -u)

    group_count=${#groups[@]}


    if [[ "$group_count" -eq 1 ]]; then
        group="${groups[0]}"
        echo "Група не вказана, буде оброблено для групи: $group"
    elif [[ "$group_count" -eq 0 ]]; then
        echo "ПОМИЛКА: групу не знайдено у файлі '$file'!" >&2
        exit 1
    else
        echo "Група не вказана, доступні групи:"
        for g in "${groups[@]}"; do
            echo " - $g"
        done

        echo -n "Будь ласка, вкажіть групу! "
        exit 1
    fi

fi


awk -F',' -v group="$group" -v outFile="$outFile" -v quiet="$quiet" '
BEGIN {
    print "Subject,Start Date,Start Time,End Time,Description" > outFile;
    counter = 1;
}
{
    for (i=1; i<=NF; i++) gsub(/"/, "", $i);

    if ($1 ~ /ПЗПІ-[0-9]+-[0-9]+/ && $1 ~ group) {
        subject = $1
        sub(/^ПЗПІ-[0-9]+-[0-9]+ - /, "", subject)

        desc = $12;

        split($2, d1, ".");
        start_date = sprintf("%02d/%02d/%04d", d1[2], d1[1], d1[3]);

        split($3, t1, ":");
        split($5, t2, ":");

        ampm1 = (t1[1] >= 12) ? "PM" : "AM";
        hour1 = t1[1] % 12; if (hour1 == 0) hour1 = 12;
        start_time = sprintf("%02d:%02d %s", hour1, t1[2], ampm1);

        ampm2 = (t2[1] >= 12) ? "PM" : "AM";
        hour2 = t2[1] % 12; if (hour2 == 0) hour2 = 12;
        end_time = sprintf("%02d:%02d %s", hour2, t2[2], ampm2);

        split($12, description, " ");
        if (description[2] == "Лб"){
            counter += 0.5
        }
        else{
            counter++;
        }

        line = sprintf("\"%s #%d\",\"%s\",\"%s\",\"%s\",\"%s\"", subject, counter, start_date, start_time, end_time, desc);
        print line >> outFile;
        if (quiet == 0) print line;
        
    }
}
' fixed_file.csv


echo -e "\nФайл збережено: $outFile"

rm -f temp_group_file.csv
rm -f fixed_file.csv
