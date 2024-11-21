'''
A nuestro equipo de desarrollo de software nos ha llegado la petición de un cliente para el
diseño y desarrollo de una aplicación de gestión de pedidos.
El cliente necesita una aplicación en donde un posible cliente se registre en la aplicación
para realizar un pedido de los productos que están disponibles en la tienda online.
'''
registro = {}
nombre = ""
dni= ""
busqueda = ""
lista_compra = []

import random

# Base de datos de clientes y productos
registro = {}
productos_disponibles = {"manzana", "sardinas", "macarrones", "galletas", "cocacola", "pepino"}

# Menú principal
def menu():
    print("Est es el Menú Principal: ")
    print("1. Registrar cliente")
    print("2. Ver clientes registrados")
    print("3. Buscar cliente por nombre")
    print("4. Realizar una compra")
    print("5. Buscar pedido por número")
    print("6. Salir")
    return input("Elige una opción: ")

# Registrar un cliente
def registrar_cliente():
    nombre = input("Ingresa el nombre del cliente: ")
    if nombre in registro:
        print("El cliente ya está registrado.")
        return
    
    dni = input(f"Ingresa el DNI de {nombre}: ")
    numero_pedido = random.randint(1, 100)
    registro[nombre] = {"dni": dni, "numero_pedido": numero_pedido, "compras": []}
    print(f"Cliente {nombre} registrado con número de pedido {numero_pedido}.")

# Ver clientes registrados
def ver_clientes():
    if not registro:
        print("No hay clientes registrados.")
        return
    print("Estos son los Clientes Registrados: ")
    for nombre, datos in registro.items():
        print(f"{nombre}: DNI {datos['dni']}, Número de pedido {datos['numero_pedido']}")

# Buscar un cliente por nombre
def buscar_cliente():
    nombre = input("Ingresa el nombre del cliente que deseas buscar: ")
    if nombre in registro:
        datos = registro[nombre]
        print(f"{nombre}: DNI {datos['dni']}, Número de pedido {datos['numero_pedido']}")
    else:
        print("Cliente no encontrado.")

# Realizar una compra
def realizar_compra():
    nombre = input("Ingresa tu nombre para iniciar sesión: ")
    if nombre not in registro:
        print("El cliente no está registrado. Por favor, regístrate primero.")
        return

    print("Estos son los productos disponibles: ")
    for producto in productos_disponibles:
        print(f"- {producto}")
    
    while True:
        producto = input("Ingresa un producto que quieras comprar (o escribe 'fin' para terminar): ")
        if producto == "fin":
            break
        if producto in productos_disponibles:
            lista_compra.append(producto)
        else:
            print("Producto no disponible. Intenta nuevamente.")

    if lista_compra:
        registro[nombre]["compras"].append({"numero_pedido": registro[nombre]["numero_pedido"], "productos": lista_compra})
        print(f"Compra realizada con éxito. Productos comprados: {', '.join(lista_compra)}")
    else:
        print("No se realizaron compras.")

# Buscar un pedido por número
def buscar_pedido():
    try:
        numero_pedido = int(input("Ingresa el número del pedido que deseas buscar: "))
    except ValueError:
        print("Número de pedido inválido. Intenta nuevamente.")
        return

    for nombre, datos in registro.items():
        for compra in datos["compras"]:
            if compra["numero_pedido"] == numero_pedido:
                print("\n--- Pedido Encontrado ---")
                print(f"Cliente: {nombre}")
                print(f"DNI: {datos['dni']}")
                print(f"Productos: {', '.join(compra['productos'])}")
                return
    print("No se encontró un pedido con ese número.")

# Ejecución del programa
while True:
    opcion = menu()
    if opcion == "1":
        registrar_cliente()
    elif opcion == "2":
        ver_clientes()
    elif opcion == "3":
        buscar_cliente()
    elif opcion == "4":
        realizar_compra()
    elif opcion == "5":
        buscar_pedido()
    elif opcion == "6":
        print("Gracias por usar esta aplicacion, hasta pronto.")
        break
    else:
        print("Opción no válida. Por favor, intententelo de nuevo.")





