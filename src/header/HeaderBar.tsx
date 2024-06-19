function HeaderBar() {
  return (
    <div className=" flex items-center justify-between h-[65px] bg-red-200">
      <div>
        <img className="w-[200px]" src="images/logo.png" alt="logo" />
      </div>
      <div className="flex gap-10">
        <div>Tel: 038393 31283</div>
        <div>E-Mail: fewo.heider@gmail.com</div>
      </div>
    </div>
  );
}

export default HeaderBar;
