function MobileMenu({
  isOpen,
  setIsOpen,
}: {
  isOpen: boolean;
  setIsOpen: React.Dispatch<React.SetStateAction<boolean>>;
}) {
  return (
    <div
      onClick={() => setIsOpen(!isOpen)}
      className={`${
        isOpen ? "fixed top-[64px] right-0" : "hidden"
      } flex flex-col gap-4 w-[210px] px-4 py-2 bg-red-200 z-[1000]`}
    >
      <a className="hide" href="#home">
        HOME
      </a>
      <a className="hide" href="#einblicke">
        EINBLICKE
      </a>
      <a className="hide" href="#preise">
        PREISE & BELEGUNG
      </a>
      <a className="hide" href="#anreise">
        ANREISE & KONTAKT
      </a>
    </div>
  );
}

export default MobileMenu;
