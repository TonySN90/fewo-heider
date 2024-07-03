function NavbarDesktop() {
  const listElStyles =
    "top-0 cursor-pointer transition-all hover:translate-y-[-4px] hover:text-amber-500 ";

  return (
    <nav className="hidden md:block h-[60px] sticky top-0 bg-white z-50">
      <ul className="flex items-center justify-center h-full gap-10 uppercase shadow-lg shadow-stone-100">
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
