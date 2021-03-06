# Objetivo

Segundo ideia de [Rubens Kuhl Jr](https://puck.nether.net/pipermail/cisco-nsp/2006-April/029662.html)
 gerar um grafico a partir de dados de trafego coletados via netflow. 
A coleta de dados permite um sem numero de analises, inclusive de [seguranca.](https://www.usenix.org/legacy/publications/library/proceedings/lisa2000/full_papers/navarro/navarro_html/index.html)

# Instalacao, no servidor

Baixe a biblioteca [Goutter](http://get.sensiolabs.org/goutte.phar) do PHP e coloque-a no diretorio projeto/

E necessario instalar os pacotes flow-tools e o banco de dados mysql para armazenar os logs, assim como o suporte a PHP5 do seu LAMPs. 

Durante a instalacao sera solicitada a senha para o usuario administrador do banco de dados, que deve ser copiada para o script flow-mysql-export
e para o arquivo projeto/db.php
     sudo apt-get install flow-tools mysql-server php5-mysql

Copie os arquivos do diretorio projeto/ para o seu servidor web
     sudo cp -va projeto /var/www

Para criar o banco de dados, use o comando abaixo [(fonte)](http://www.kelvinism.com/2008/12/netflow-into-mysql-with-flow-tools_5439.html)
     mysql -u root -p < sql.txt

Copie o arquivo flow-mysql-export para /usr/bin
     sudo cp flow-mysql-export /usr/bin/

E necessario criar um diretorio para receber os dados coletados
     mkdir -p /var/flow/

Crie o arquivo /etc/flow-tools/flow-capture.conf com
    $ sudo bash
    # echo \-w/var/flow -E100M 0/0/6343 -R /usr/bin/flow-mysql-export > /etc/flow-tools/flow-capture.conf

Inicie a captura com
     service flow-capture start

# Instalacao, no roteador
Para enviar flows da interface desejada para o coletor (neste exemplo em 10.1.0.1:6343) use as configuracoes exemplo abaixo (referentes ao seu roteador especifico)

## Cisco 
    > enable
    # conf t
    ip cef
    if flow-export version 5 peer-as
    ip flow-export source interface fa0/0
    ip flow-export destination 10.1.0.1 6343
    ip flow-cache timeout active 1
    int fa0/0
    ip route-cache flow
    end
    write

## Brocade
    (config)#sflow enable
    (config)#sflow destination 10.1.0.1 6343
    (config)#sflow polling-interval 1
    (config)#sflow sample 1024
    (config)#interface ethernet 1/6
    (config-if-e1000-1/6)#sflow forwarding

## Nprobe (windows)
Para listar os indices das interfaces
    nprobe /c -h
Para executar a captura
    nprobe /i nprobe_service --collector 10.1.0.1:6343 --interface 0 --local-networks 192.168.1.0/24 --flow-version 5 --local-traffic-direction --in-iface-idx 1 --out-iface-idx 2 --lifetime-timeout 60 --idle-timeout 15 

## Mikrotik
    /ip traffic-flow
    set enable=yes
    set interfaces=sfp0
    target add address=10.1.0.1:6343 version=9

## [Linux usando nprobe](http://www.ntop.org/products/nprobe/)
Instale o nprobe conforme instrucoes de http://apt.ntop.org
    sudo nprobe -i eth0 -n 10.1.0.1:6343

** Juniper
    [edit interfaces dfc-0/0/0]
    unit 0 {
    family inet {
    address 10.1.0.2/32 { # Address of the Routing Engine for the DFC PIC.
    destination 10.1.0.1; # Address of DFC PIC; used by the
    # control source to correspond with the monitoring station.
    }
    }
    }
    unit 1 { # Receives data packets on this logical interface.
    family inet; 
    }
    unit 2 { # Sends copies of matched packets from this logical interface.
    family inet; 
    }
    [edit chassis]
    fpc 0 {
    pic 0 {
    monitoring-services application dynamic-flow-capture;
    }
    }

# Para ver o resultado
Acesse o servidor web com
     firefox http://localhost/projeto/
