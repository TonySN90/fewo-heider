function NavbarDesktop() {
  const listElStyles =
    "cursor-pointer transition-all hover:translate-y-[-4px] hover:text-amber-500";

  return (
    <nav className="h-[60px]">
      <ul className="flex items-center justify-center h-full gap-10 uppercase shadow-lg">
        <li className={listElStyles}>
          <a href="#">Home</a>
        </li>
        <li className={listElStyles}>
          <a href="#">Einblicke</a>
        </li>
        <li className={listElStyles}>
          <a href="#">Preise & Belegung</a>
        </li>
        <li className={listElStyles}>
          <a href="#">Anreise & Kontakt</a>
        </li>
      </ul>
    </nav>
  );
}

export default NavbarDesktop;
