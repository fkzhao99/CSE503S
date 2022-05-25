list = []
list.append([])
list.append(['789'])
for a in list:
    for b in a:
        if b == None:
            b = ''
        print('123' + b + '456')